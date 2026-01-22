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
        $dataInicio = $request->get('data_inicio', now()->subDays(30)->toDateString());
        $dataFim = $request->get('data_fim', now()->toDateString());

        $queryBase = Solicitacao::whereBetween('created_at', [
            $dataInicio . ' 00:00:00', 
            $dataFim . ' 23:59:59'
        ]);

        $totalChamados = (clone $queryBase)->count();
        $totalResolvidos = (clone $queryBase)->where('status', 'resolvido')->count();
        
        $tmrHoras = (clone $queryBase)->where('status', 'resolvido')
            ->whereNotNull('resolvido_em')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, resolvido_em)) as avg_tmr')
            ->value('avg_tmr') ?? 0;

        $foraDoSla = (clone $queryBase)->where('status', '!=', 'resolvido')
            ->where('created_at', '<', now()->subDay())
            ->count();

        $prioridades = (clone $queryBase)->select('prioridade', DB::raw('count(*) as total'))
            ->groupBy('prioridade')
            ->pluck('total', 'prioridade');

        $statusCounts = (clone $queryBase)->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $estatisticasMotivo = (clone $queryBase)->select('motivo_contato', DB::raw('count(*) as total'))
            ->groupBy('motivo_contato')
            ->get();

        $tendenciaSemanal = (clone $queryBase)->selectRaw('DATE(created_at) as data, count(*) as total')
            ->groupBy('data')
            ->orderBy('data')
            ->get();

        return view('dashboard', compact(
            'totalChamados', 'totalResolvidos', 'tmrHoras', 'foraDoSla', 
            'prioridades', 'statusCounts', 'estatisticasMotivo', 'tendenciaSemanal',
            'dataInicio', 'dataFim'
        ));
    }

    /**
     * Listagem administrativa.
     */
    public function index(Request $request)
    {
        $query = Solicitacao::query();

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
     * Atualiza o chamado e registra o timestamp de resolução.
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

        if ($validated['status'] === 'resolvido' && $solicitacao->status !== 'resolvido') {
            $dados['resolvido_em'] = now();
        }

        $solicitacao->update($dados);
        return back()->with('sucesso', 'Chamado atualizado com sucesso!');
    }

    /**
     * Método para criação via Formulário Web.
     */
    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome_solicitante'     => 'nullable|string|max:20',
            'telefone_solicitante' => 'required|string|max:20',
            'email_solicitante'    => 'required|email',
            'motivo_contato'       => 'required|string',
            'descricao_duvida'     => 'required|string',
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

    /**
     * NOVO: Método para receber chamados vindos do Bot (API).
     */
    public function storeFromBot(Request $request)
    {
        // Validação dos campos mapeados no integrations.py do Bot
        $validated = $request->validate([
            'solicitante_nome'     => 'required|string|max:255',
            'solicitante_telefone' => 'required|string|max:20',
            'solicitante_email'    => 'required|email',
            'categoria'            => 'required|string',
            'descricao'            => 'required|string',
        ]);

        $protocolo = 'BOT-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        $solicitacao = Solicitacao::create([
            'nome_solicitante'     => $validated['solicitante_nome'],
            'telefone_solicitante' => $validated['solicitante_telefone'],
            'email_solicitante'    => $validated['solicitante_email'],
            'motivo_contato'       => $validated['categoria'],
            'descricao_duvida'     => $validated['descricao'],
            'protocolo'            => $protocolo,
            'status'               => 'novo',
            'prioridade'           => 'media',
        ]);

        return response()->json([
            'sucesso'   => true,
            'protocolo' => $protocolo,
            'message'   => 'Chamado registrado com sucesso via WhatsApp.'
        ], 201);
    }

    /**
     * Consulta pública de protocolo.
     */
    public function acompanhar(Request $request)
    {
        $request->validate(['protocolo' => 'required|string']);
        $solicitacao = Solicitacao::where('protocolo', $request->protocolo)->first();
        return view('acompanhar', compact('solicitacao'))->with('busca_realizada', true);
    }

    /**
     * Remove o registro e arquivos.
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