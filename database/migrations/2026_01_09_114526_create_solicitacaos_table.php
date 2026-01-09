<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('solicitacaos', function (Blueprint $table) {
        $table->id();
        // Adicionando os campos do formulário
        $table->string('nome_solicitante');
        $table->string('telefone_solicitante');
        $table->string('email_solicitante');
        $table->enum('motivo_contato', ['suporte', 'duvida', 'solicitacao', 'outro']);
        $table->text('descricao_duvida');
        $table->string('arquivo_anexo')->nullable(); // Campo para o caminho do arquivo
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitacaos');
    }
};
