<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
    Schema::table('solicitacaos', function (Blueprint $table) {
        // Define a urgência do chamado
        $table->enum('prioridade', ['baixa', 'media', 'alta', 'urgente'])->default('media')->after('status');
        // Registra quando o chamado foi finalizado para cálculo de TMR
        $table->timestamp('resolvido_em')->nullable()->after('resposta_admin');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solicitacaos', function (Blueprint $table) {
            //
        });
    }
};
