<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('quiz_perguntas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->text('texto_pergunta');
            $table->integer('coins_recompensa')->default(10);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('quiz_perguntas'); }
};