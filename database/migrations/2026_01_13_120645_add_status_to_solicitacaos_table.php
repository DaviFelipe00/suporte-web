<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // database/migrations/xxxx_add_status_to_solicitacaos_table.php
public function up(): void
{
    Schema::table('solicitacaos', function (Blueprint $table) {
        $table->enum('status', ['novo', 'pendente', 'em_andamento', 'resolvido'])->default('novo')->after('descricao_duvida');
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
