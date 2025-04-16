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
        Schema::table('analogos', function (Blueprint $table) {
            $table->unsignedBigInteger('analogo_marca_id');
            $table->unsignedBigInteger('analogo_modelo_id');
            $table->unsignedBigInteger('analogo_diametro_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('analogos', function (Blueprint $table) {
            $table->dropIfExists('analogo_marca_id');
            $table->dropIfExists('analogo_modelo_id');
            $table->dropIfExists('analogo_diametro_id');
        });
    }
};
