<?php

namespace App\Http\Controllers;

use App\Models\Solicitacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;


class SolicitacaoController extends Controller
{

    public function dashboard()
{
    // Total Geral de Chamados
    $totalChamados = Solicitacao::count();

    // Chamados abertos HOJE
    $chamadosHoje = Solicitacao::whereDate('created_at', Carbon::today())->count();

    // Top motivos de contato (Agrupamento por motivo)
    $estatisticasMotivo = Solicitacao::select('motivo_contato', \DB::raw('count(*) as total'))
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
    
    // app/Http/Controllers/SolicitacaoController.php

public function update(Request $request, Solicitacao $solicitacao)
{
    $request->validate([
        'status' => 'required|in:novo,pendente,em_andamento,resolvido'
    ]);

    $solicitacao->update(['status' => $request->status]);

    return back()->with('sucesso', 'Status do chamado atualizado com sucesso!');
}

public function destroy(Solicitacao $solicitacao)
{
    $solicitacao->delete();
    return back()->with('sucesso', 'Chamado excluído permanentemente.');
}

    /**
     * Processa o envio do formulário e salva os anexos.
     */
    public function store(Request $request)
    {
        // 1. Validação: anexo.* garante a validação de cada arquivo no array
        $dados = $request->validate([
            'nome_solicitante'     => 'required|string|max:255',
            'telefone_solicitante' => 'required',
            'email_solicitante'    => 'required|email',
            'motivo_contato'       => 'required',
            'descricao_duvida'     => 'required',
            'anexo.*'              => 'nullable|file|mimes:jpg,png,pdf|max:2048', 
        ]);

        // 2. Processamento de múltiplos uploads
        if ($request->hasFile('anexo')) {
            $arquivosSalvos = [];
            
            foreach ($request->file('anexo') as $arquivo) {
                // Armazena na pasta 'public/evidencias' e guarda o caminho
                $arquivosSalvos[] = $arquivo->store('evidencias', 'public');
            }
            
            // Convertemos o array de caminhos em JSON para salvar na coluna 'arquivo_anexo'
            $dados['arquivo_anexo'] = json_encode($arquivosSalvos); 
        }

        // 3. Persistência no Banco de Dados
        // Removemos 'anexo' pois ele veio do formulário, mas a coluna no banco é 'arquivo_anexo'
        unset($dados['anexo']); 
        
        Solicitacao::create($dados);

        return back()->with('sucesso', 'Solicitação e anexos enviados com sucesso!');
    }
}