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
    Schema::table('missoes', function (Blueprint $table) {
        $table->enum('tipo_missao', ['compra', 'engajamento', 'social'])->default('compra')->after('descricao');
        $table->string('meta_item_tipo')->nullable()->after('tipo_missao'); // Ex: 'produto', 'categoria', 'quiz'
        $table->unsignedBigInteger('meta_item_id')->nullable()->after('meta_item_tipo');
        $table->integer('meta_quantidade')->default(1)->after('meta_item_id');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('missoes', function (Blueprint $table) {
            //
        });
    }
};