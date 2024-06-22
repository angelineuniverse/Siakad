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
        Schema::create('t_mata_kuliah_tabs', function (Blueprint $table) {
            $table->id();
            $table->char('code',13);
            $table->char('title',200);
            $table->unsignedBigInteger('t_dosen_tabs_id')->nullable();
            $table->unsignedTinyInteger('m_semester_tabs_id')->nullable();
            $table->unsignedTinyInteger('m_semester_periode_tabs_id')->nullable();
            $table->unsignedTinyInteger('m_fakultas_tabs_id')->nullable();
            $table->unsignedSmallInteger('m_jurusan_tabs_id')->nullable();
            $table->char('days',10)->nullable();
            $table->time('times');
            $table->smallInteger('bobot_sks');
            $table->unsignedMediumInteger('m_status_tabs_id')->default(5);
            $table->timestamps();
            $table->foreign('m_status_tabs_id')->references('id')->on('m_status_tabs')->cascadeOnDelete();
            $table->foreign('t_dosen_tabs_id')->references('id')->on('t_dosen_tabs')->nullOnDelete();
            $table->foreign('m_semester_tabs_id')->references('id')->on('m_semester_tabs')->nullOnDelete();
            $table->foreign('m_semester_periode_tabs_id')->references('id')->on('m_semester_periode_tabs')->nullOnDelete();
            $table->foreign('m_fakultas_tabs_id')->references('id')->on('m_fakultas_tabs')->nullOnDelete();
            $table->foreign('m_jurusan_tabs_id')->references('id')->on('m_jurusan_tabs')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_mata_kuliah_tabs');
    }
};
