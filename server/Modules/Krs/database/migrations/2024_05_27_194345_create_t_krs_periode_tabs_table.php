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
        Schema::create('t_krs_periode_tabs', function (Blueprint $table) { // Periode Akademik
            $table->integerIncrements('id');
            $table->char('title',100);
            $table->date('start');
            $table->date('end');
            $table->unsignedTinyInteger('m_semester_periode_tabs_id');
            $table->unsignedMediumInteger('m_status_tabs_id');
            $table->timestamps();
            $table->foreign('m_status_tabs_id')->references('id')->on('m_status_tabs')->cascadeOnDelete();
            $table->foreign('m_semester_periode_tabs_id')->references('id')->on('m_semester_periode_tabs')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_krs_periode_tabs');
    }
};
