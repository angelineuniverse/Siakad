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
        Schema::create('t_mahasiswa_detail_general_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('t_mahasiswa_tabs_id');
            $table->unsignedTinyInteger('m_gender_tabs_id')->nullable();
            $table->unsignedTinyInteger('m_married_tabs_id')->nullable();
            $table->unsignedTinyInteger('m_religion_tabs_id')->nullable();
            $table->char('place_of_birth',30);
            $table->date('date_of_birth');
            $table->char('private_email',40)->nullable();
            $table->char('no_nik',40);
            $table->char('no_kk',40);
            $table->char('no_paspor',40)->nullable();
            $table->char('no_phone',15);
            $table->unsignedTinyInteger('m_blood_tabs_id')->nullable();
            $table->tinyInteger('body_weight')->nullable();
            $table->tinyInteger('body_height')->nullable();
            $table->char('jobs',40)->nullable();
            $table->char('jobs_agency',40)->nullable();
            $table->unsignedInteger('m_country_tabs_id')->nullable();
            
            $table->foreign('t_mahasiswa_tabs_id')->references('id')
                ->on('t_mahasiswa_tabs')->onDelete('cascade');
            $table->foreign('m_gender_tabs_id')->references('id')
                ->on('m_gender_tabs')->nullOnDelete();
            $table->foreign('m_married_tabs_id')->references('id')
                ->on('m_married_tabs')->nullOnDelete();
            $table->foreign('m_religion_tabs_id')->references('id')
                ->on('m_religion_tabs')->nullOnDelete();
            $table->foreign('m_blood_tabs_id')->references('id')
                ->on('m_blood_tabs')->nullOnDelete();
            $table->foreign('m_country_tabs_id')->references('id')
                ->on('m_country_tabs')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_mahasiswa_detail_general_tabs');
    }
};
