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
     * Exibe o resumo operacional (Dashboard) para o Admin.
     * Dados para indicadores de status, motivos e atividades recentes.
     */
    public function dashboard()
    {
        $totalChamados = Solicitacao::count();
        $chamadosHoje = Solicitacao::whereDate('created_at', Carbon::today())->count();

        // Agrupamento por status para os cards superiores
        $statusCounts = Solicitacao::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // Volume por motivo para o gráfico de barras
        $estatisticasMotivo = Solicitacao::select('motivo_contato', DB::raw('count(*) as total'))
            ->groupBy('motivo_contato')
            ->get();

        // Feed de atividade recente (últimos 5)
        $ultimosChamados = Solicitacao::latest()->take(5)->get();

        return view('dashboard', compact(
            'totalChamados', 
            'chamadosHoje', 
            'statusCounts', 
            'estatisticasMotivo', 
            'ultimosChamados'
        ));
    }

    /**
     * Exibe a listagem principal para o Painel Admin.
     */
    public function index()
    {
        $chamados = Solicitacao::latest()->get();
        return view('admin.index', compact('chamados'));
    }

    /**
     * Atualiza o chamado (Status e Resposta Técnica).
     * Nota: O erro de edição geralmente é resolvido adicionando 'resposta_admin' ao $fillable do Model.
     */
    public function update(Request $request, Solicitacao $solicitacao)
    {
        $request->validate([
            'status' => 'required|in:novo,pendente,em_andamento,resolvido',
            'resposta_admin' => 'nullable|string'
        ]);

        $solicitacao->update([
            'status' => $request->status,
            'resposta_admin' => $request->resposta_admin
        ]);

        return back()->with('sucesso', 'Chamado atualizado com sucesso!');
    }

    /**
     * Remove o chamado permanentemente.
     */
    public function destroy(Solicitacao $solicitacao)
    {
        $solicitacao->delete();
        return back()->with('sucesso', 'Chamado excluído permanentemente.');
    }

    /**
     * Cria a solicitação, gera protocolo e processa anexos.
     */
    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome_solicitante'     => 'required|string|max:255',
            'telefone_solicitante' => 'required',
            'email_solicitante'    => 'required|email',
            'motivo_contato'       => 'required',
            'descricao_duvida'     => 'required',
            'anexo.*'              => 'nullable|file|mimes:jpg,png,pdf|max:2048', 
        ]);

        // Geração de Protocolo Sênior: AAAAMMDD-HASH
        $protocolo = date('Ymd') . '-' . strtoupper(Str::random(6));
        $dados['protocolo'] = $protocolo;

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
     * Consulta pública de protocolo (Sem necessidade de login).
     */
    public function acompanhar(Request $request)
    {
        $request->validate(['protocolo' => 'required|string']);

        $solicitacao = Solicitacao::where('protocolo', $request->protocolo)->first();

        return view('acompanhar', compact('solicitacao'))->with('busca_realizada', true);
    }
}