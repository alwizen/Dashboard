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
        Schema::create('daily_cctv_reports', function (Blueprint $table) {
            $table->id();
            $table->date(('report_date'));
            $table->integer('cctv_count');
            $table->integer('active_cctv_count');
            $table->integer('inactive_cctv_count');
            $table->text('report_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_cctv_reports');
    }
};
