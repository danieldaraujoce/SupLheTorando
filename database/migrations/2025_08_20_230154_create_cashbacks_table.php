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
    Schema::create('cashbacks', function (Blueprint $table) {
        $table->id();
        $table->string('titulo');
        $table->text('descricao')->nullable();
        $table->enum('tipo', ['porcentagem', 'fixo'])->comment('O cashback é em % ou um valor fixo?');
        $table->decimal('valor', 10, 2);
        $table->decimal('valor_minimo_compra', 10, 2)->nullable()->comment('Valor mínimo da compra para o cashback ser aplicado');
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
        Schema::dropIfExists('cashbacks');
    }
};