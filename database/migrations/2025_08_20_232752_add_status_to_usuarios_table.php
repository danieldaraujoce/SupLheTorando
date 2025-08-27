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
    Schema::table('usuarios', function (Blueprint $table) {
        // Adiciona a coluna 'status' após a coluna 'coins'
        // 'ativo' será o valor padrão para novos clientes
        $table->string('status')->default('ativo')->after('coins');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            //
        });
    }
};