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
     */
    public function dashboard(Request $request)
    {
        // Define o período: busca da request ou padrão dos últimos 30 dias
        $dataInicio = $request->get('data_inicio', now()->subDays(30)->toDateString());
        $dataFim = $request->get('data_fim', now()->toDateString());

        // Query base com filtro de data para consistência em todos os KPIs
        $queryBase = Solicitacao::whereBetween('created_at', [
            $dataInicio . ' 00:00:00', 
            $dataFim . ' 23:59:59'
        ]);

        // KPIs Principais
        $totalChamados = (clone $queryBase)->count();
        $totalResolvidos = (clone $queryBase)->where('status', 'resolvido')->count();
        
        // Tempo Médio de Resolução (TMR) em horas
        $tmrHoras = (clone $queryBase)->where('status', 'resolvido')
            ->whereNotNull('resolvido_em')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, resolvido_em)) as avg_tmr')
            ->value('avg_tmr') ?? 0;

        // SLA: Chamados não resolvidos que passaram de 24h
        $foraDoSla = (clone $queryBase)->where('status', '!=', 'resolvido')
            ->where('created_at', '<', now()->subDay())
            ->count();

        // Distribuição por Prioridade e Status
        $prioridades = (clone $queryBase)->select('prioridade', DB::raw('count(*) as total'))
            ->groupBy('prioridade')
            ->pluck('total', 'prioridade');

        $statusCounts = (clone $queryBase)->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // Volume por Motivo de Contato
        $estatisticasMotivo = (clone $queryBase)->select('motivo_contato', DB::raw('count(*) as total'))
            ->groupBy('motivo_contato')
            ->get();

        // Tendência Temporal (Agrupado por Dia)
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
     * Listagem administrativa com filtros e ordenação por data de abertura.
     */
    public function index(Request $request)
    {
        $query = Solicitacao::query();

        // Filtro por período de abertura
        if ($request->filled('data_inicio') && $request->filled('data_fim')) {
            $query->whereBetween('created_at', [
                $request->data_inicio . ' 00:00:00',
                $request->data_fim . ' 23:59:59'
            ]);
        }

        // Ordena pelos mais recentes para priorizar novos chamados
        $chamados = $query->latest()->get();
        
        return view('admin.index', compact('chamados'));
    }

    /**
     * Atualiza o status, resposta técnica e métricas de conclusão.
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

        // Se o status for alterado para resolvido agora, grava a data de conclusão
        if ($validated['status'] === 'resolvido' && $solicitacao->status !== 'resolvido') {
            $dados['resolvido_em'] = now();
        }

        $solicitacao->update($dados);

        return back()->with('sucesso', 'Chamado #' . $solicitacao->protocolo . ' atualizado com sucesso!');
    }

    /**
     * Cria uma nova solicitação com geração automática de protocolo.
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

        // Geração de protocolo único: ANO+MES+DIA-RANDOM
        $protocolo = date('Ymd') . '-' . strtoupper(Str::random(6));
        $dados['protocolo'] = $protocolo;
        $dados['status'] = 'novo'; // Status inicial padrão
        $dados['prioridade'] = $request->prioridade ?? 'media';

        // Processamento de múltiplos anexos (salvos como JSON no banco)
        if ($request->hasFile('anexo')) {
            $arquivosSalvos = [];
            foreach ($request->file('anexo') as $arquivo) {
                $arquivosSalvos[] = $arquivo->store('evidencias', 'public');
            }
            $dados['arquivo_anexo'] = json_encode($arquivosSalvos); 
        }

        // Remove o campo temporário de arquivo antes de salvar no Model
        unset($dados['anexo']); 
        
        Solicitacao::create($dados);

        return back()->with('sucesso', 'Sua solicitação foi enviada com sucesso!')
                     ->with('protocolo', $protocolo);
    }

    /**
     * Busca um chamado pelo protocolo para acompanhamento do cliente.
     */
    public function acompanhar(Request $request)
    {
        $request->validate(['protocolo' => 'required|string']);
        
        $solicitacao = Solicitacao::where('protocolo', $request->protocolo)->first();

        return view('acompanhar', compact('solicitacao'))->with('busca_realizada', true);
    }

    /**
     * Exclusão permanente de um chamado.
     */
    public function destroy(Solicitacao $solicitacao)
    {
        // Remove arquivos físicos antes de deletar o registro (Boa prática de limpeza)
        if ($solicitacao->arquivo_anexo) {
            $anexos = json_decode($solicitacao->arquivo_anexo, true);
            if (is_array($anexos)) {
                foreach ($anexos as $caminho) {
                    Storage::disk('public')->delete($caminho);
                }
            }
        }

        $solicitacao->delete();
        return back()->with('sucesso', 'Chamado removido do sistema.');
    }
}