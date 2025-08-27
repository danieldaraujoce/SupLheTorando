<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('caixa_surpresa_itens', function (Blueprint $table) {
            // Nova coluna para guardar o nome do prêmio customizado
            $table->string('nome_customizado')->nullable()->after('valor_premio');
        });
    }

    public function down(): void
    {
        Schema::table('caixa_surpresa_itens', function (Blueprint $table) {
            $table->dropColumn('nome_customizado');
        });
    }
};