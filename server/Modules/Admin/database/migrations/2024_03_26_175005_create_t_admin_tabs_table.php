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
        Schema::create('t_admin_tabs', function (Blueprint $table) {
            $table->id();
            $table->char('email',30)->unique();
            $table->char('name',50);
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->char('phone',15)->nullable();
            $table->unsignedSmallInteger('m_role_tab_id')->nullable();
            $table->tinyInteger('active')->default(0)->comment('0 = not active, 1 = active');
            $table->timestamps();
            $table->foreign('m_role_tab_id')->references('id')->on('m_role_tabs')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_admin_tabs');
    }
};
