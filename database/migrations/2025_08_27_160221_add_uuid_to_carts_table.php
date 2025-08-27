<?php
// Em database/migrations/xxxx_xx_xx_xxxxxx_add_uuid_to_carts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carrinhos', function (Blueprint $table) {
            // Adiciona um campo UUID para ser o identificador único e seguro para a API.
            $table->uuid('uuid')->nullable()->unique()->after('id');
            // Altera o status para incluir os novos estados do fluxo.
            $table->string('status')->default('aberto')->change(); // Garante que o campo suporta 'aberto', 'finalizado', 'concluido'
        });
    }

    public function down(): void
    {
        Schema::table('carrinhos', function (Blueprint $table) {
            $table->dropColumn('uuid');
            $table->string('status')->default('aberto')->change();
        });
    }
};