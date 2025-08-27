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
    Schema::create('ofertas_mes', function (Blueprint $table) {
        $table->id();
        $table->string('titulo');
        $table->string('imagem_capa');
        $table->string('arquivo_url');
        $table->date('data_inicio');
        $table->date('data_fim');
        $table->string('status')->default('ativo');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oferta_mes');
    }
};