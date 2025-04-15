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
        Schema::create('interfases', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->string('interfase_marca_id');
            $table->string('interfase_tipo_id');
            $table->string('interfase_diametro_id');
            $table->string('interfase_altura_g_id');
            $table->double('interfase_altura_h_id');
            $table->string('rotacion');
            $table->string('referencia');
            $table->integer('unidades');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interfases');
    }
};
