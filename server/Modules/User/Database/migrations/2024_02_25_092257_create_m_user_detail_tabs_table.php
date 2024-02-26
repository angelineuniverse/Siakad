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
        Schema::create('m_user_detail_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('users_tabs_id');
            $table->unsignedInteger('m_jurusan_tabs_id')->nullable();
            $table->char('nik',25);
            $table->unsignedTinyInteger('m_gender_tabs_id')->nullable();
            $table->enum('religion', ['Islam','Protestan','Katolik','Buddha','Hindu','None'])->default('Islam');
            $table->char('contact')->nullable();
            $table->tinyInteger('married')->default('0')->comment('0 = not married, 1 = married');
            $table->char('ttl',30);
            $table->date('birthday');
            $table->text('address')->nullable();
            $table->unsignedInteger('m_city_tabs_id')->nullable();
            $table->unsignedInteger('m_province_tabs_id')->nullable();
            $table->foreign('users_tabs_id')->references('id')->on('users_tabs')->onDelete('cascade');
            $table->foreign('m_jurusan_tabs_id')->references('id')->on('m_jurusan_tabs')->nullOnDelete();
            $table->foreign('m_city_tabs_id')->references('id')->on('m_city_tabs')->nullOnDelete();
            $table->foreign('m_province_tabs_id')->references('id')->on('m_province_tabs')->nullOnDelete();
            $table->foreign('m_gender_tabs_id')->references('id')->on('m_gender_tabs')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_user_detail_tabs');
    }
};
