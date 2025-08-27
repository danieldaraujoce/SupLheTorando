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
        Schema::create('cashback_user', function (Blueprint $table) {
            $table->id();
            
            // Chave estrangeira para a tabela 'usuarios'
            $table->foreignId('user_id')->constrained('usuarios')->onDelete('cascade');
            
            // Chave estrangeira para a tabela 'cashbacks'
            $table->foreignId('cashback_id')->constrained('cashbacks')->onDelete('cascade');
            
            $table->timestamps();

            $table->unique(['user_id', 'cashback_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashback_user');
    }
};