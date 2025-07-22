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
        Schema::table('interfase_diametro', function (Blueprint $table) {
            $table->string(column: 'valor')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interfase_diametro', function (Blueprint $table) {
            $table->double('valor')->change();
        });
    }
};
