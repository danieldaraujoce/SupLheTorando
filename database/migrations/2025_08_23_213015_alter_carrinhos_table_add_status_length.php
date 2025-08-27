<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('carrinhos', function (Blueprint $table) {
            $table->string('status', 30)->change();
        });
    }

    public function down()
    {
        Schema::table('carrinhos', function (Blueprint $table) {
            // Você pode reverter o status para um tamanho menor, se desejar
            // $table->string('status', 15)->change();
        });
    }
};