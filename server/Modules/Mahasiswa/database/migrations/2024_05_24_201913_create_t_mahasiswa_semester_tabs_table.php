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
        Schema::create('t_mahasiswa_semester_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('t_mahasiswa_tabs_id');
            $table->unsignedTinyInteger('m_semester_tabs_id');
            $table->unsignedTinyInteger('m_semester_periode_tabs_id');
            $table->tinyInteger('active');
            $table->foreign('t_mahasiswa_tabs_id')->references('id')->on('t_mahasiswa_tabs')->cascadeOnDelete();
            $table->foreign('m_semester_tabs_id')->references('id')->on('m_semester_tabs')->cascadeOnDelete();
            $table->foreign('m_semester_periode_tabs_id')->references('id')->on('m_semester_periode_tabs')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_mahasiswa_semester_tabs');
    }
};
