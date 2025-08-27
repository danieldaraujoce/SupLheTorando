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
    Schema::create('carrinho_itens', function (Blueprint $table) {
        $table->id();
        $table->foreignId('carrinho_id')->constrained('carrinhos')->onDelete('cascade');
        $table->foreignId('produto_id')->constrained('produtos')->onDelete('cascade');
        $table->integer('quantidade');
        $table->decimal('preco_unitario', 10, 2)->comment('Preço do produto no momento da adição');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carrinho_itens');
    }
};