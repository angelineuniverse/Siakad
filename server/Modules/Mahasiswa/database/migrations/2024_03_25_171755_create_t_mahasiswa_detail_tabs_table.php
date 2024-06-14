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
        Schema::create('t_mahasiswa_detail_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('t_mahasiswa_tabs_id');
            $table->char('birthday_city');
            $table->date('birthday_date');
            $table->char('no_nik');
            $table->char('no_kk');
            $table->char('old_school',30)->nullable();
            $table->char('phone');
            $table->unsignedTinyInteger('m_gender_tabs_id')->nullable();
            $table->unsignedTinyInteger('m_blood_tabs_id')->nullable();
            $table->unsignedTinyInteger('m_married_tabs_id')->nullable();
            $table->unsignedTinyInteger('m_religion_tabs_id')->nullable();
            $table->unsignedInteger('m_city_tabs_id')->nullable();
            $table->unsignedInteger('m_province_tabs_id')->nullable();
            $table->string('address');
            $table->foreign('m_blood_tabs_id')->references('id')->on('m_blood_tabs')->nullOnDelete();
            $table->foreign('t_mahasiswa_tabs_id')->references('id')->on('t_mahasiswa_tabs')->cascadeOnDelete();
            $table->foreign('m_gender_tabs_id')->references('id')->on('m_gender_tabs')->nullOnDelete();
            $table->foreign('m_married_tabs_id')->references('id')->on('m_married_tabs')->nullOnDelete();
            $table->foreign('m_religion_tabs_id')->references('id')->on('m_religion_tabs')->nullOnDelete();
            $table->foreign('m_city_tabs_id')->references('id')->on('m_city_tabs')->nullOnDelete();
            $table->foreign('m_province_tabs_id')->references('id')->on('m_province_tabs')->nullOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_mahasiswa_detail_tabs');
    }
};
