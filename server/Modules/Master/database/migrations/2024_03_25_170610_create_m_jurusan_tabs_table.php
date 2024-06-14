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
        Schema::create('m_jurusan_tabs', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->char('code',11);
            $table->char('title');
            $table->tinyInteger('active');
            $table->tinyInteger('for_all')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_jurusan_tabs');
    }
};
