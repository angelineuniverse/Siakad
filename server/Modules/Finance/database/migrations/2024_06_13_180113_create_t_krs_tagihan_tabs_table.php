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
        Schema::create('t_krs_tagihan_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('t_krs_tabs_id');
            $table->integer('payment')->default(0);
            $table->tinyInteger('validation')->default(1)->comment('1 = valid');
            $table->timestamps();
            $table->foreign('t_krs_tabs_id')->references('id')->on('t_krs_tabs')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_krs_tagihan_tabs');
    }
};
