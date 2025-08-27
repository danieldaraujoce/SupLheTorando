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
        // Este método é executado quando você roda "php artisan migrate"
        Schema::table('usuarios', function (Blueprint $table) {
            // Aqui definimos a nova coluna 'whatsapp'
            // ->nullable() significa que o campo não é obrigatório
            // ->after('senha') coloca a coluna depois da coluna 'senha' na tabela
            $table->string('whatsapp')->nullable()->after('senha');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Este método é para o caso de precisarmos desfazer a alteração
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn('whatsapp');
        });
    }
};