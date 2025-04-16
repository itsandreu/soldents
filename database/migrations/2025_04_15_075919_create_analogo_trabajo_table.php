<?php

use App\Models\Analogo;
use App\Models\Trabajo;
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
        Schema::create('analogo_trabajo', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Analogo::class);
            $table->foreignIdFor(Trabajo::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analogo_trabajo');
    }
};
