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
        Schema::table('tornillos', function (Blueprint $table) {
            $table->unsignedBigInteger('tornillo_marca_id');
            $table->unsignedBigInteger('tornillo_modelo_id');
            $table->unsignedBigInteger('tornillo_tipo_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tornillos', function (Blueprint $table) {
            //
        });
    }
};
