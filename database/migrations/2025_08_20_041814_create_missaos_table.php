<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('missoes', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao');
            $table->integer('coins_recompensa')->default(0);
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->string('status', 20)->default('inativa');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('missoes'); }
};