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
    Schema::table('recompensas', function (Blueprint $table) {
        // Adiciona a coluna 'status' após a coluna 'tipo'
        // 'ativo' será o valor padrão para novas recompensas
        $table->string('status')->default('ativo')->after('tipo');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recompensas', function (Blueprint $table) {
            //
        });
    }
};