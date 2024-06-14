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
        Schema::create('m_menu_tabs', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->smallInteger('order')->nullable();
            $table->char('title',30);
            $table->char('url',30);
            $table->char('icon', 100);
            $table->tinyInteger('parent_id')->default(0);
            $table->tinyInteger('active')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_menu_tabs');
    }
};
