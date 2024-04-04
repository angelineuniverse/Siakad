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
        Schema::create('m_code_tabs', function (Blueprint $table) {
            $table->char('prefix');
            $table->integer('order');
            $table->smallInteger('length');
            $table->integer('year');
            $table->char('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_code_tabs');
    }
};
