<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Solicitacao extends Model
{
    use HasFactory;

    /**
     * Atributos que podem ser preenchidos em massa.
     * Incluímos os campos do formulário web, os dados extraídos pelo bot 
     * e os campos de gestão administrativa e métricas.
     */
    protected $fillable = [
        'protocolo',            // Gerado automaticamente
        'nome_solicitante', 
        'telefone_solicitante', 
        'email_solicitante', 
        'motivo_contato', 
        'descricao_duvida', 
        'arquivo_anexo', 
        'status',               // Gerido pelo admin e bot
        'resposta_admin',       // Campo de feedback do suporte
        'prioridade',           // Baixa, média, alta ou urgente
        'resolvido_em',         // Timestamp para cálculo de TMR (Tempo Médio de Resolução)
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     * Isso garante que o Laravel trate as datas e JSONs corretamente.
     */
    protected $casts = [
        'resolvido_em' => 'datetime',
        'arquivo_anexo' => 'array', // Converte o JSON do banco para array automaticamente
    ];
}