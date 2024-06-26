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
        Schema::create('m_fakultas_tabs', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->char('code',11);
            $table->char('title');
            $table->tinyInteger('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_fakultas_tabs');
    }
};
