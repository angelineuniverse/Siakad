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
            $table->char('email')->unique();
            $table->char('name');
            $table->string('password');
            $table->string('avatar');
            $table->char('nim',20)->nullable();
            $table->tinyInteger('active')->default(0)->comment('0 = not activated, 1 = activated');
            $table->tinyInteger('deleted')->default(0)->comment('0 = not deleted, 1 = deleted');
            $table->timestamps();
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
