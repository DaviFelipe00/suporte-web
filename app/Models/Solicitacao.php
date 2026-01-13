<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solicitacao extends Model
{
    // Definimos quais campos o formulário pode preencher no banco
   protected $fillable = [
    'nome_solicitante', 'telefone_solicitante', 'email_solicitante', 
    'motivo_contato', 'descricao_duvida', 'arquivo_anexo', 'status'
];
}