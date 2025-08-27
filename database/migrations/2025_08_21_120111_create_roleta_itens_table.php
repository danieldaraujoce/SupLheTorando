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
    Schema::create('roleta_itens', function (Blueprint $table) {
        $table->id();
        $table->foreignId('roleta_id')->constrained('roletas')->onDelete('cascade');
        $table->string('descricao');
        $table->string('tipo_premio'); // 'coins', 'cupom', 'produto', 'giro_extra', 'nada'
        $table->unsignedBigInteger('premio_id')->nullable(); // ID do produto ou cupom
        $table->integer('valor_premio')->nullable(); // Quantidade de moedas ou giros
        $table->decimal('chance_percentual', 5, 2);
        $table->string('cor_slice')->default('#FFFFFF'); // Cor da fatia na roleta
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roleta_itens');
    }
};