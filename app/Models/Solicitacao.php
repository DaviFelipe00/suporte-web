<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solicitacao extends Model
{
    /**
     * Atributos que podem ser preenchidos em massa.
     * * Incluímos o 'protocolo' para permitir que o sistema salve 
     * o código gerado automaticamente no Controller.
     */
   protected $fillable = [
    'protocolo', 
    'nome_solicitante', 
    'telefone_solicitante', 
    'email_solicitante', 
    'motivo_contato', 
    'descricao_duvida', 
    'arquivo_anexo', 
    'status',
    'resposta_admin' // ADICIONE ESTA LINHA
];
}