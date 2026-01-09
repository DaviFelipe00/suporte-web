<?php

namespace App\Http\Controllers;

use App\Models\Solicitacao;
use Illuminate\Http\Request;

class SolicitacaoController extends Controller
{
    public function store(Request $request)
{
    // 1. Validação (Garante que o arquivo seja seguro)
    $dados = $request->validate([
        'nome_solicitante'     => 'required|string|max:255',
        'telefone_solicitante' => 'required',
        'email_solicitante'    => 'required|email',
        'motivo_contato'       => 'required',
        'descricao_duvida'     => 'required',
        'anexo'                => 'nullable|file|mimes:jpg,png,pdf|max:2048', // Máximo 2MB
    ]);

    // 2. Processamento do Upload
    if ($request->hasFile('anexo')) {
        // Salva o arquivo na pasta 'storage/app/public/evidencias'
        $caminho = $request->file('anexo')->store('evidencias', 'public');
        
        // Adicionamos o caminho ao array de dados para salvar no banco
        $dados['arquivo_anexo'] = $caminho;
    }

    // 3. Salvar no Banco
    // Removemos o 'anexo' original do array, pois no banco a coluna chama 'arquivo_anexo'
    unset($dados['anexo']); 
    
    \App\Models\Solicitacao::create($dados);

    return back()->with('sucesso', 'Solicitação e anexo enviados com sucesso!');
}
}