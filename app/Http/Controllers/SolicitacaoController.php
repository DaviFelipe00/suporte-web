<?php

namespace App\Http\Controllers;

use App\Models\Solicitacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SolicitacaoController extends Controller
{
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