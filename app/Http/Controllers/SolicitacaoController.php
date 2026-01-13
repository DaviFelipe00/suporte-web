<?php

namespace App\Http\Controllers;

use App\Models\Solicitacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // Necessário para gerar o protocolo
use Carbon\Carbon;

class SolicitacaoController extends Controller
{
    /**
     * Exibe o resumo operacional (Dashboard) para o Admin.
     */
    public function dashboard()
    {
        // Total Geral de Chamados
        $totalChamados = Solicitacao::count();

        // Chamados abertos HOJE
        $chamadosHoje = Solicitacao::whereDate('created_at', Carbon::today())->count();

        // Top motivos de contato (Agrupamento por motivo)
        $estatisticasMotivo = Solicitacao::select('motivo_contato', DB::raw('count(*) as total'))
            ->groupBy('motivo_contato')
            ->get();

        return view('dashboard', compact('totalChamados', 'chamadosHoje', 'estatisticasMotivo'));
    }

    /**
     * Exibe a listagem de chamados para o painel administrativo.
     */
    public function index()
    {
        // Busca todos os chamados ordenados pelos mais recentes
        $chamados = Solicitacao::latest()->get();

        return view('admin.index', compact('chamados'));
    }

    /**
     * Atualiza o status de um chamado específico.
     */
    public function update(Request $request, Solicitacao $solicitacao)
    {
        $request->validate([
            'status' => 'required|in:novo,pendente,em_andamento,resolvido'
        ]);

        $solicitacao->update(['status' => $request->status]);

        return back()->with('sucesso', 'Status do chamado atualizado com sucesso!');
    }

    /**
     * Remove um chamado permanentemente.
     */
    public function destroy(Solicitacao $solicitacao)
    {
        $solicitacao->delete();
        return back()->with('sucesso', 'Chamado excluído permanentemente.');
    }

    /**
     * Processa o envio do formulário, gera Protocolo e salva anexos.
     */
    public function store(Request $request)
    {
        // 1. Validação dos dados
        $dados = $request->validate([
            'nome_solicitante'     => 'required|string|max:255',
            'telefone_solicitante' => 'required',
            'email_solicitante'    => 'required|email',
            'motivo_contato'       => 'required',
            'descricao_duvida'     => 'required',
            'anexo.*'              => 'nullable|file|mimes:jpg,png,pdf|max:2048', 
        ]);

        // 2. Geração de Protocolo Único (Padrão Sênior: Data + Hash)
        $protocolo = date('Ymd') . '-' . strtoupper(Str::random(6));
        $dados['protocolo'] = $protocolo;

        // 3. Processamento de múltiplos uploads
        if ($request->hasFile('anexo')) {
            $arquivosSalvos = [];
            
            foreach ($request->file('anexo') as $arquivo) {
                $arquivosSalvos[] = $arquivo->store('evidencias', 'public');
            }
            
            $dados['arquivo_anexo'] = json_encode($arquivosSalvos); 
        }

        unset($dados['anexo']); 
        
        // 4. Salva no banco de dados
        Solicitacao::create($dados);

        // 5. Retorna com o protocolo para ser exibido ao cliente
        return back()->with('sucesso', 'Solicitação enviada com sucesso! Guarde seu protocolo.')
                     ->with('protocolo', $protocolo);
    }

    /**
     * Permite ao cliente acompanhar o status via protocolo.
     */
    public function acompanhar(Request $request)
    {
        $request->validate([
            'protocolo' => 'required|string'
        ]);

        // Busca pela string exata do protocolo
        $solicitacao = Solicitacao::where('protocolo', $request->protocolo)->first();

        return view('acompanhar', compact('solicitacao'))->with('busca_realizada', true);
    }
}