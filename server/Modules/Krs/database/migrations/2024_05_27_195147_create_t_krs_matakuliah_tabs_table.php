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
        Schema::create('t_krs_matakuliah_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('t_krs_tabs_id');
            $table->unsignedBigInteger('t_mata_kuliah_tabs_id');
            $table->float('nilai')->default(0);
            $table->foreign('t_krs_tabs_id')->references('id')->on('t_krs_tabs')->cascadeOnDelete();
            $table->foreign('t_mata_kuliah_tabs_id')->references('id')->on('t_mata_kuliah_tabs')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_krs_matakuliah_tabs');
    }
};
