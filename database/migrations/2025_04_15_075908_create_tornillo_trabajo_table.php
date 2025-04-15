<?php

use App\Models\Tornillo;
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
        Schema::create('tornillo_trabajo', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Tornillo::class);
            $table->foreignIdFor(Trabajo::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tornillo_trabajo');
    }
};
