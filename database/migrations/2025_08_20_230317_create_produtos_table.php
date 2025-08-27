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
    Schema::create('produtos', function (Blueprint $table) {
        $table->id();
        $table->string('nome');
        $table->string('codigo_barras')->unique()->nullable(); // Essencial para o Scan & Go
        $table->foreignId('categoria_id')->nullable()->constrained('categorias_produtos')->onDelete('set null');
        $table->text('descricao')->nullable();
        $table->decimal('preco', 10, 2);
        $table->integer('estoque')->default(0);
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
        Schema::dropIfExists('produtos');
    }
};