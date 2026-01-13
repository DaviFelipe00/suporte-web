<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Cria a coluna permitindo nulo temporariamente
        Schema::table('solicitacaos', function (Blueprint $table) {
            $table->string('protocolo')->nullable()->after('id');
        });

        // 2. Gera protocolos únicos para os chamados que já existem no banco
        $chamados = DB::table('solicitacaos')->whereNull('protocolo')->get();
        foreach ($chamados as $chamado) {
            $novoProtocolo = date('Ymd') . '-' . strtoupper(Str::random(6));
            DB::table('solicitacaos')
                ->where('id', $chamado->id)
                ->update(['protocolo' => $novoProtocolo]);
        }

        // 3. Com todos os dados preenchidos, aplicamos a restrição UNIQUE e NOT NULL
        Schema::table('solicitacaos', function (Blueprint $table) {
            $table->string('protocolo')->unique()->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('solicitacaos', function (Blueprint $table) {
            $table->dropColumn('protocolo');
        });
    }
};