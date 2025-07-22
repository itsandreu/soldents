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
        Schema::create('trabajo_tipo_trabajo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trabajo_id')->constrained()->onDelete('cascade');
            $table->foreignId('tipo_trabajo_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trabajo_tipo_trabajo');
    }
};
