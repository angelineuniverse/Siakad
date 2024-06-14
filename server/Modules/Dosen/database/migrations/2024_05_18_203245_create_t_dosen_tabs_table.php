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
        Schema::create('t_dosen_tabs', function (Blueprint $table) {
            $table->id();
            $table->char('code',13);
            $table->char('name',50);
            $table->char('email',30)->unique();
            $table->string('avatar');
            $table->string('phone',15);
            $table->unsignedTinyInteger('m_gender_tabs_id')->nullable();
            $table->unsignedTinyInteger('m_fakultas_tabs_id')->nullable();
            $table->unsignedSmallInteger('m_jurusan_tabs_id')->nullable();
            $table->unsignedMediumInteger('m_status_tabs_id')->nullable();
            $table->text('address');
            $table->timestamps();
            $table->foreign('m_gender_tabs_id')->references('id')->on('m_gender_tabs')->nullOnDelete();
            $table->foreign('m_fakultas_tabs_id')->references('id')->on('m_fakultas_tabs')->nullOnDelete();
            $table->foreign('m_jurusan_tabs_id')->references('id')->on('m_jurusan_tabs')->nullOnDelete();
            $table->foreign('m_status_tabs_id')->references('id')->on('m_status_tabs')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_dosen_tabs');
    }
};
