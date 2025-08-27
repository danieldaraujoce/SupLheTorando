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
    Schema::table('recompensas', function (Blueprint $table) {
        $table->foreignId('nivel_necessario_id')->nullable()->constrained('niveis');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recompensas', function (Blueprint $table) {
            //
        });
    }
};