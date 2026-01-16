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
     * Refatorado para suportar filtros de data globais.
     */
    public function dashboard(Request $request)
    {
        // Define o período: busca da request ou padrão dos últimos 30 dias
        $dataInicio = $request->get('data_inicio', now()->subDays(30)->toDateString());
        $dataFim = $request->get('data_fim', now()->toDateString());

        // Query base para garantir que todas as métricas usem o mesmo filtro
        $queryBase = Solicitacao::whereBetween('created_at', [
            $dataInicio . ' 00:00:00', 
            $dataFim . ' 23:59:59'
        ]);

        // KPIs de Eficiência (usando clone para não afetar a query original)
        $totalChamados = (clone $queryBase)->count();
        $totalResolvidos = (clone $queryBase)->where('status', 'resolvido')->count();
        
        // Cálculo do TMR em horas
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

        // Agrupamento por status
        $statusCounts = (clone $queryBase)->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // Volume por motivo
        $estatisticasMotivo = (clone $queryBase)->select('motivo_contato', DB::raw('count(*) as total'))
            ->groupBy('motivo_contato')
            ->get();

        // Tendência ajustada ao período do filtro
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
     * Exibe a listagem administrativa com suporte a filtro de data.
     */
    public function index(Request $request)
    {
        $query = Solicitacao::query();

        // Aplicação condicional do filtro de data
        if ($request->filled('data_inicio') && $request->filled('data_fim')) {
            $query->whereBetween('created_at', [
                $request->data_inicio . ' 00:00:00',
                $request->data_fim . ' 23:59:59'
            ]);
        }

        $chamados = $query->latest()->get();
        return view('admin.index', compact('chamados'));
    }

    /**
     * Atualiza o chamado e registra o tempo de resolução se necessário.
     */
    public function update(Request $request, Solicitacao $solicitacao)
    {
        $request->validate([
            'status' => 'required|in:novo,pendente,em_andamento,resolvido',
            'prioridade' => 'nullable|in:baixa,media,alta,urgente',
            'resposta_admin' => 'nullable|string'
        ]);

        $dados = [
            'status' => $request->status,
            'resposta_admin' => $request->resposta_admin,
            'prioridade' => $request->prioridade ?? $solicitacao->prioridade
        ];

        // Lógica: Se o status mudar para resolvido, marca o timestamp de conclusão
        if ($request->status === 'resolvido' && $solicitacao->status !== 'resolvido') {
            $dados['resolvido_em'] = now();
        }

        $solicitacao->update($dados);

        return back()->with('sucesso', 'Chamado atualizado com sucesso!');
    }

    /**
     * Cria a solicitação com geração de protocolo aleatório.
     */
    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome_solicitante'     => 'required|string|max:255',
            'telefone_solicitante' => 'required',
            'email_solicitante'    => 'required|email',
            'motivo_contato'       => 'required',
            'descricao_duvida'     => 'required',
            'prioridade'           => 'nullable|in:baixa,media,alta,urgente',
            'anexo.*'              => 'nullable|file|mimes:jpg,png,pdf|max:2048', 
        ]);

        $protocolo = date('Ymd') . '-' . strtoupper(Str::random(6));
        $dados['protocolo'] = $protocolo;
        $dados['prioridade'] = $request->prioridade ?? 'media';

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

    public function acompanhar(Request $request)
    {
        $request->validate(['protocolo' => 'required|string']);
        $solicitacao = Solicitacao::where('protocolo', $request->protocolo)->first();

        return view('acompanhar', compact('solicitacao'))->with('busca_realizada', true);
    }

    public function destroy(Solicitacao $solicitacao)
    {
        $solicitacao->delete();
        return back()->with('sucesso', 'Chamado excluído permanentemente.');
    }
}