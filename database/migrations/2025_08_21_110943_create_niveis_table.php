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
    Schema::create('niveis', function (Blueprint $table) {
        $table->id();
        $table->string('nome'); // Ex: Bronze, Prata, Ouro
        $table->string('imagem_emblema')->nullable();
        $table->integer('requisito_minimo_coins')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('niveis');
    }
};