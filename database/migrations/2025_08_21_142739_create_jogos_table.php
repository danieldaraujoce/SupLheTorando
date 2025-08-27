<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
    Schema::create('jogos', function (Blueprint $table) {
        $table->id();
        $table->string('nome'); // Ex: Carrinho Premiado
        $table->string('slug')->unique(); // Ex: carrinho-premiado
        $table->text('descricao');
        $table->string('status')->default('ativo');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jogos');
    }
};