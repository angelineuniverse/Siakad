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
        Schema::create('users_tabs', function (Blueprint $table) {
            $table->id();
            $table->char('email',70)->unique();
            $table->char('name',50);
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->char('nim',50)->nullable()->unique();
            $table->char('code',15)->unique();
            $table->tinyInteger('status_adm')->default(0)->comment("0: not done, 1: done");
            $table->tinyInteger('active')->default(0)->comment("0: not active, 1: active");
            $table->tinyInteger('deleted')->default(0)->comment("0: not deleted, 1: deleted");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * 1 . Create Account User
     * 
     * 2 . Detail User
     *  - Jurusan nya apa ?
     *  - Menggenerate NIM by Jurusan
     */
    public function down(): void
    {
        Schema::dropIfExists('users_tabs');
    }
};
