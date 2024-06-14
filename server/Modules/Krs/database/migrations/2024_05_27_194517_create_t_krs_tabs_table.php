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
        Schema::create('t_krs_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('t_krs_periode_tabs_id');
            $table->unsignedBigInteger('t_mahasiswa_tabs_id');
            $table->unsignedBigInteger('t_mahasiswa_semester_tabs_id');
            $table->integer('tagihan')->default(0);
            $table->unsignedMediumInteger('m_status_tabs_id');
            $table->dateTime('active_date');
            $table->timestamps();
            $table->foreign('t_mahasiswa_semester_tabs_id')->references('id')->on('t_mahasiswa_semester_tabs')->cascadeOnDelete();
            $table->foreign('t_krs_periode_tabs_id')->references('id')->on('t_krs_periode_tabs')->cascadeOnDelete();
            $table->foreign('t_mahasiswa_tabs_id')->references('id')->on('t_mahasiswa_tabs')->cascadeOnDelete();
            $table->foreign('m_status_tabs_id')->references('id')->on('m_status_tabs')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_krs_tabs');
    }
};
