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
        Schema::create('m_generate_nim_tabs', function (Blueprint $table) {
            $table->char('m_jurusan_tabs_id', 3); // ID Jurusan
            $table->char('fakultas',2); // 33,22,11
            $table->char('jurusan',2); // 01,02,03
            $table->tinyInteger('start')->default(1); // 1
            $table->char('length',4); // 0001
            $table->integer('years'); // 24
            $table->string('description'); // Penggunaan Code
        });
    }

    /**
     * Reverse the migrations. 3302240001
     * 11 => Keuangan
     * 33 => Teknik
     * 02 => Elektro
     * years
     * urut
     */
    public function down(): void
    {
        Schema::dropIfExists('m_generate_nim_tabs');
    }
};
