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
        Schema::create('t_menu_role_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('m_menu_tab_id');
            $table->unsignedSmallInteger('m_role_tab_id');
            $table->foreign('m_menu_tab_id')->references('id')->on('m_menu_tabs')->onDelete('cascade');
            $table->foreign('m_role_tab_id')->references('id')->on('m_role_tabs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_menu_role_tabs');
    }
};
