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
            $table->char('name',50);
            $table->char('nim',50)->unique();
            $table->char('email',50)->unique();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->char('code',15)->unique();
            $table->tinyInteger('status_administration')->default(0)->comment("0: not done, 1: done");
            $table->tinyInteger('status_active')->default(0)->comment("0: not active, 1: active");
            $table->tinyInteger('deleted')->default(0)->comment("0: not deleted, 1: deleted");
            $table->unsignedTinyInteger('m_mahasiswa_register_type_tabs_id')->nullable();
            $table->unsignedTinyInteger('m_mahasiswa_register_enroll_tabs_id')->nullable();
            $table->timestamps();
            $table->foreign('m_mahasiswa_register_type_tabs_id')->references('id')
                ->on('m_mahasiswa_register_type_tabs')->nullOnDelete();
            $table->foreign('m_mahasiswa_register_enroll_tabs_id')->references('id')
                ->on('m_mahasiswa_register_enroll_tabs')->nullOnDelete();
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
