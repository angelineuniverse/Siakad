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
            $table->char('fakultas',2);
            $table->char('jurusan',2);
            $table->tinyInteger('start')->default(1);
            $table->char('length',4);
            $table->integer('years');
            $table->string('description');
        });
    }

    /**
     * Reverse the migrations. 3302240001
     */
    public function down(): void
    {
        Schema::dropIfExists('m_generate_nim_tabs');
    }
};
