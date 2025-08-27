<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promocoes', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->string('imagem_promocao')->nullable();
            $table->enum('tipo_desconto', ['porcentagem', 'fixo']);
            $table->decimal('valor_desconto', 10, 2);
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->string('status')->default('ativo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promocoes');
    }
};