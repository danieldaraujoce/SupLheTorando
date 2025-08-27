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
        Schema::table('cupons', function (Blueprint $table) {
            // Coluna para definir o tipo do cupom
            $table->string('tipo_cupom')->default('normal')->after('status');
            
            // Coluna para cupons-relâmpago com validade em horas
            $table->integer('horas_validade')->nullable()->after('tipo_cupom');

            // Coluna para o bônus de moedas do programa de fidelidade
            $table->integer('coins_extra_fidelidade')->default(0)->after('horas_validade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cupons', function (Blueprint $table) {
            //
        });
    }
};
