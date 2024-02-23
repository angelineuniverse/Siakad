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
            $table->char('nim',50)->unique();
            $table->date('birthday');
            $table->char('code',15)->unique();
            $table->unsignedTinyInteger('m_gender_tabs_id')->nullable();
            $table->tinyInteger('active')->default(0)->comment("0: not active, 1: active");
            $table->tinyInteger('deleted')->default(0)->comment("0: not deleted, 1: deleted");
            $table->timestamps();
            $table->foreign('m_gender_tabs_id')->references('id')->on('m_gender_tabs')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_tabs');
    }
};
