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
            $table->char('code',10);
            $table->unsignedBigInteger('t_krs_tabs_id');
            $table->integer('payment')->default(0);
            $table->unsignedMediumInteger('m_status_tabs_id')->default(8)->comment('8 = valid');
            $table->timestamps();
            $table->foreign('t_krs_tabs_id')->references('id')->on('t_krs_tabs')->cascadeOnDelete();
            $table->foreign('m_status_tabs_id')->references('id')->on('m_status_tabs')->cascadeOnDelete();
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
