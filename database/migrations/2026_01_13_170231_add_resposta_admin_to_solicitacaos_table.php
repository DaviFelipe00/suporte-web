<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa a migration para adicionar o campo de resposta técnica.
     */
    public function up(): void
    {
        Schema::table('solicitacaos', function (Blueprint $table) {
            // Adiciona a coluna text para suportar respostas longas do suporte
            $table->text('resposta_admin')->nullable()->after('status');
        });
    }

    /**
     * Reverte a migration removendo o campo.
     */
    public function down(): void
    {
        Schema::table('solicitacaos', function (Blueprint $table) {
            // Remove a coluna caso seja necessário fazer um rollback
            $table->dropColumn('resposta_admin');
        });
    }
};