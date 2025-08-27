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
    Schema::create('promocao_produto', function (Blueprint $table) {
        $table->id();
        $table->foreignId('promocao_id')->constrained('promocoes')->onDelete('cascade');
        $table->foreignId('produto_id')->constrained('produtos')->onDelete('cascade');
        // Adicionamos a quantidade, caso uma promoção exija mais de uma unidade de um produto
        $table->integer('quantidade_necessaria')->default(1);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promocao_produto');
    }
};