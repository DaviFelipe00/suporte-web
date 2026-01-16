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
    Schema::create('equipamentos', function (Blueprint $table) {
        $table->id();
        $table->string('nome');
        $table->string('tipo'); // Ex: Notebook, Monitor, Periférico
        $table->string('numero_serie')->unique();
        $table->string('empresa'); // Empresa onde o item está alocado
        $table->enum('status', ['disponivel', 'em_uso', 'manutencao', 'descartado'])->default('disponivel');
        $table->text('observacoes')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipamentos');
    }
};
