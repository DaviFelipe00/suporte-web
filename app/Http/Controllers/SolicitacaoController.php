<?php

namespace App\Http\Controllers;

use App\Models\Solicitacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SolicitacaoController extends Controller
{
    /**
     * Exibe o resumo operacional e métricas avançadas (Dashboard).
     * Refatorado para garantir que todos os KPIs respeitem o filtro de data.
     */
    public function dashboard(Request $request)
    {
        // Define o período: busca da request ou padrão dos últimos 30 dias
        $dataInicio = $request->get('data_inicio', now()->subDays(30)->toDateString());
        $dataFim = $request->get('data_fim', now()->toDateString());

        // Query base para consistência de dados em todos os cálculos
        $queryBase = Solicitacao::whereBetween('created_at', [
            $dataInicio . ' 00:00:00', 
            $dataFim . ' 23:59:59'
        ]);

        // KPIs de Eficiência
        $totalChamados = (clone $queryBase)->count();
        $totalResolvidos = (clone $queryBase)->where('status', 'resolvido')->count();
        
        // Cálculo do TMR (Tempo Médio de Resolução) em horas
        $tmrHoras = (clone $queryBase)->where('status', 'resolvido')
            ->whereNotNull('resolvido_em')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, resolvido_em)) as avg_tmr')
            ->value('avg_tmr') ?? 0;

        // SLA: Chamados pendentes há mais de 24h
        $foraDoSla = (clone $queryBase)->where('status', '!=', 'resolvido')
            ->where('created_at', '<', now()->subDay())
            ->count();

        // Distribuição por Prioridade
        $prioridades = (clone $queryBase)->select('prioridade', DB::raw('count(*) as total'))
            ->groupBy('prioridade')
            ->pluck('total', 'prioridade');

        // Agrupamento por Status
        $statusCounts = (clone $queryBase)->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // Volume por Motivo de Contato
        $estatisticasMotivo = (clone $queryBase)->select('motivo_contato', DB::raw('count(*) as total'))
            ->groupBy('motivo_contato')
            ->get();

        // Tendência Temporal
        $tendenciaSemanal = (clone $queryBase)->selectRaw('DATE(created_at) as data, count(*) as total')
            ->groupBy('data')
            ->orderBy('data')
            ->get();

        return view('dashboard', compact(
            'totalChamados', 
            'totalResolvidos',
            'tmrHoras', 
            'foraDoSla', 
            'prioridades', 
            'statusCounts', 
            'estatisticasMotivo', 
            'tendenciaSemanal',
            'dataInicio',
            'dataFim'
        ));
    }

    /**
     * Listagem administrativa com suporte a filtro de data e ordenação.
     */
    public function index(Request $request)
    {
        $query = Solicitacao::query();

        // Filtro condicional por data de abertura
        if ($request->filled('data_inicio') && $request->filled('data_fim')) {
            $query->whereBetween('created_at', [
                $request->data_inicio . ' 00:00:00',
                $request->data_fim . ' 23:59:59'
            ]);
        }

        /**
         * ORDENAÇÃO: O método latest() ordena por 'created_at' de forma decrescente,
         * garantindo que os chamados mais novos apareçam primeiro.
         */
        $chamados = $query->latest()->get();
        
        return view('admin.index', compact('chamados'));
    }

    /**
     * Atualiza o chamado e registra o timestamp de resolução para métricas.
     */
    public function update(Request $request, Solicitacao $solicitacao)
    {
        $validated = $request->validate([
            'status' => 'required|in:novo,pendente,em_andamento,resolvido',
            'prioridade' => 'nullable|in:baixa,media,alta,urgente',
            'resposta_admin' => 'nullable|string|max:5000'
        ]);

        $dados = [
            'status' => $validated['status'],
            'resposta_admin' => $validated['resposta_admin'],
            'prioridade' => $validated['prioridade'] ?? $solicitacao->prioridade
        ];

        // Lógica de Negócio: Grava a data de resolução apenas na transição para o status 'resolvido'
        if ($validated['status'] === 'resolvido' && $solicitacao->status !== 'resolvido') {
            $dados['resolvido_em'] = now();
        }

        $solicitacao->update($dados);

        return back()->with('sucesso', 'Chamado atualizado com sucesso!');
    }

    /**
     * Cria a solicitação e processa anexos múltiplos.
     */
    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome_solicitante'     => 'required|string|max:255',
            'telefone_solicitante' => 'required|string|max:20',
            'email_solicitante'    => 'required|email',
            'motivo_contato'       => 'required|string',
            'descricao_duvida'     => 'required|string',
            'prioridade'           => 'nullable|in:baixa,media,alta,urgente',
            'anexo.*'              => 'nullable|file|mimes:jpg,png,pdf|max:2048', 
        ]);

        // Geração de protocolo único (Ex: 20260120-ABC123)
        $protocolo = date('Ymd') . '-' . strtoupper(Str::random(6));
        $dados['protocolo'] = $protocolo;
        $dados['prioridade'] = $request->prioridade ?? 'media';

        // Persistência de arquivos no storage público
        if ($request->hasFile('anexo')) {
            $arquivosSalvos = [];
            foreach ($request->file('anexo') as $arquivo) {
                $arquivosSalvos[] = $arquivo->store('evidencias', 'public');
            }
            $dados['arquivo_anexo'] = json_encode($arquivosSalvos); 
        }

        unset($dados['anexo']); 
        
        Solicitacao::create($dados);

        return back()->with('sucesso', 'Solicitação enviada!')
                     ->with('protocolo', $protocolo);
    }

    /**
     * Consulta pública de protocolo para o cliente.
     */
    public function acompanhar(Request $request)
    {
        $request->validate(['protocolo' => 'required|string']);
        
        $solicitacao = Solicitacao::where('protocolo', $request->protocolo)->first();

        return view('acompanhar', compact('solicitacao'))->with('busca_realizada', true);
    }

    /**
     * Remove o registro e limpa os arquivos físicos associados.
     */
    public function destroy(Solicitacao $solicitacao)
    {
        if ($solicitacao->arquivo_anexo) {
            $anexos = json_decode($solicitacao->arquivo_anexo, true);
            if (is_array($anexos)) {
                foreach ($anexos as $caminho) {
                    Storage::disk('public')->delete($caminho);
                }
            }
        }

        $solicitacao->delete();
        return back()->with('sucesso', 'Chamado excluído permanentemente.');
    }
}