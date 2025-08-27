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
    Schema::create('caixa_surpresa_itens', function (Blueprint $table) {
        $table->id();
        $table->foreignId('caixa_surpresa_id')->constrained('caixas_surpresa')->onDelete('cascade');
        $table->string('tipo_premio'); // Ex: 'produto', 'cupom', 'coins'
        $table->unsignedBigInteger('premio_id')->nullable(); // ID do produto ou cupom
        $table->integer('valor_premio')->nullable(); // Quantidade de moedas
        $table->decimal('chance_percentual', 5, 2); // Probabilidade de ganhar este item
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caixa_surpresa_itens');
    }
};