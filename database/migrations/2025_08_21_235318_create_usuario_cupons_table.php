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
        Schema::create('usuario_cupons', function (Blueprint $table) {
            $table->id();
            // Corrigido para apontar para a tabela 'usuarios'
            $table->foreignId('user_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('cupom_id')->constrained('cupons')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_cupons');
    }
};