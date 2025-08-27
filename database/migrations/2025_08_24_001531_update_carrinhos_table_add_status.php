<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Alterar a coluna para adicionar o novo valor ENUM
        DB::statement("ALTER TABLE `carrinhos` CHANGE `status` `status` ENUM('aberto','aguardando_pagamento','pago','cancelado') NOT NULL DEFAULT 'aberto'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverter a coluna para o ENUM original
        DB::statement("ALTER TABLE `carrinhos` CHANGE `status` `status` ENUM('aberto','pago','cancelado') NOT NULL DEFAULT 'aberto'");
    }
};