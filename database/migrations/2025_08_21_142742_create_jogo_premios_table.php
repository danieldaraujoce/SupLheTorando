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
    Schema::create('jogo_premios', function (Blueprint $table) {
        $table->id();
        $table->foreignId('jogo_id')->constrained('jogos')->onDelete('cascade');
        $table->string('descricao_premio');
        $table->string('tipo_premio'); // 'coins', 'cupom', 'produto'
        $table->unsignedBigInteger('premio_id')->nullable();
        $table->integer('valor_premio')->nullable();
        $table->decimal('chance_percentual', 5, 2);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jogo_premios');
    }
};