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
    Schema::create('destaques_home', function (Blueprint $table) {
        $table->id();
        $table->string('imagem');
        $table->string('titulo');
        $table->string('subtitulo')->nullable();
        $table->text('descricao');
        $table->integer('valor_moedas')->nullable();
        $table->string('texto_botao');
        $table->string('link_botao');
        $table->integer('ordem')->default(0);
        $table->string('status')->default('ativo');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destaques_home');
    }
};