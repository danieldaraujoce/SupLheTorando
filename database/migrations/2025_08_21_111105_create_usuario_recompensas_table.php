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
    Schema::create('usuario_recompensas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
        $table->foreignId('recompensa_id')->constrained('recompensas')->onDelete('cascade');
        $table->string('codigo_resgate')->unique(); // Um código único para o caixa validar
        $table->string('status')->default('resgatado'); // 'resgatado' ou 'utilizado'
        $table->timestamp('data_resgate')->nullable();
        $table->timestamp('data_utilizacao')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_recompensas');
    }
};