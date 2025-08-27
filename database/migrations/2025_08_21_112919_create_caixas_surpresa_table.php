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
    Schema::create('caixas_surpresa', function (Blueprint $table) {
        $table->id();
        $table->string('nome'); // Ex: Caixa de Natal, Caixa Surpresa de Hortifruti
        $table->text('descricao')->nullable();
        $table->string('imagem_url')->nullable();
        $table->string('status')->default('ativo');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caixas_surpresa');
    }
};