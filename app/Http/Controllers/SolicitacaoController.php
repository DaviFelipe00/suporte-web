<?php

namespace App\Http\Controllers;

use App\Models\Solicitacao;
use Illuminate\Http\Request;

class SolicitacaoController extends Controller
{
    public function store(Request $request)
{
    // 1. Validação corrigida para aceitar múltiplos arquivos
    $dados = $request->validate([
        'nome_solicitante'     => 'required|string|max:255',
        'telefone_solicitante' => 'required',
        'email_solicitante'    => 'required|email',
        'motivo_contato'       => 'required',
        'descricao_duvida'     => 'required',
        'anexo.*'              => 'nullable|file|mimes:jpg,png,pdf|max:2048', // Valida cada item do array
    ]);

    // 2. Processamento do Upload
    if ($request->hasFile('anexo')) {
        $arquivosSalvos = [];
        
        foreach ($request->file('anexo') as $arquivo) {
            // Salva na pasta 'storage/app/public/evidencias'
            $arquivosSalvos[] = $arquivo->store('evidencias', 'public');
        }
        
        // Como o banco é uma string, salvamos o caminho do primeiro ou um JSON
        // Opção: Salvar como JSON para suportar todos os arquivos enviados
        $dados['arquivo_anexo'] = json_encode($arquivosSalvos); 
    }

    // 3. Salvar no Banco
    unset($dados['anexo']); // Remove o campo do formulário que não existe na tabela
    
    \App\Models\Solicitacao::create($dados);

    return back()->with('sucesso', 'Solicitação e anexo enviados com sucesso!');
}
}