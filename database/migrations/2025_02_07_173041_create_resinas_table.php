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
        Schema::create('resinas', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['Modelos', 'Férulas', 'Encías']);
            $table->string('marca');
            $table->decimal('litros', 5, 2);
            $table->string('lote');
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resinas');
    }
};
