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
        Schema::create('t_pengumuman_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('t_admin_tabs_id');
            $table->char('code',15);
            $table->string('title');
            $table->text('description');
            $table->string('file')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->tinyInteger('active')->default(1)->comment("1 = active");
            $table->timestamps();
            $table->foreign('t_admin_tabs_id')->references('id')->on('t_admin_tabs')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_pengumuman_tabs');
    }
};
