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
        Schema::create('t_mahasiswa_tabs', function (Blueprint $table) {
            $table->id();
            $table->char('nim',20)->nullable();
            $table->char('name');
            $table->char('email')->unique();
            $table->string('password');
            $table->char('curiculum');
            $table->date('date_in');
            $table->string('avatar')->nullable();
            $table->unsignedBigInteger('t_mahasiswa_periode_tabs_id');
            $table->unsignedTinyInteger('m_register_type_tabs_id')->nullable(); // jenis pendaftaran
            $table->unsignedTinyInteger('m_register_enroll_tabs_id')->nullable(); // jalur pendaftaran
            $table->unsignedTinyInteger('m_fakultas_tabs_id');
            $table->unsignedSmallInteger('m_jurusan_tabs_id');
            $table->tinyInteger('active')->default(0)->comment('0 = not activated, 1 = activated');
            $table->tinyInteger('deleted')->default(0)->comment('0 = not deleted, 1 = deleted');
            $table->timestamps();
            $table->foreign('m_fakultas_tabs_id')->references('id')->on('m_fakultas_tabs')->cascadeOnDelete();
            $table->foreign('m_jurusan_tabs_id')->references('id')->on('m_jurusan_tabs')->cascadeOnDelete();
            $table->foreign('t_mahasiswa_periode_tabs_id')->references('id')->on('t_mahasiswa_periode_tabs')->cascadeOnDelete();
            $table->foreign('m_register_type_tabs_id')->references('id')->on('m_register_type_tabs')->nullOnDelete();
            $table->foreign('m_register_enroll_tabs_id')->references('id')->on('m_register_enroll_tabs')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_mahasiswa_tabs');
    }
};
