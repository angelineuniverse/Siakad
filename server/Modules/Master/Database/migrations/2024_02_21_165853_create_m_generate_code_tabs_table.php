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
        Schema::create('m_generate_code_tabs', function (Blueprint $table) {
            $table->char('serial',3);
            $table->char('prefix',3);
            $table->tinyInteger('start')->default(1);
            $table->tinyInteger('length',3);
            $table->integer('years');
            $table->string('description');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_generate_code_tabs');
    }
};
