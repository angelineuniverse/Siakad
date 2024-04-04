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
            $table->char('old_school',30);
            $table->char('phone');
            $table->unsignedTinyInteger('m_gender_tabs_id');
            $table->unsignedTinyInteger('m_blood_tabs_id')->nullable();
            $table->unsignedTinyInteger('m_married_tabs_id');
            $table->unsignedTinyInteger('m_religion_tabs_id');
            $table->unsignedTinyInteger('m_city_tabs_id');
            $table->unsignedTinyInteger('m_province_tabs_id');
            $table->string('address');
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
