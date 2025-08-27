<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usuario_quiz_progresso', function (Blueprint $table) {
            $table->id();

            // A CORREÇÃO ESTÁ AQUI: Dizemos explicitamente para usar a tabela 'usuarios'
            $table->foreignId('user_id')->constrained('usuarios')->onDelete('cascade');

            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->foreignId('quiz_pergunta_id')->constrained('quiz_perguntas')->onDelete('cascade');
            $table->foreignId('quiz_resposta_id')->constrained('quiz_respostas')->onDelete('cascade');
            
            $table->boolean('correta');
            $table->timestamps();

            $table->unique(['user_id', 'quiz_pergunta_id']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_quiz_progresso');
    }
};