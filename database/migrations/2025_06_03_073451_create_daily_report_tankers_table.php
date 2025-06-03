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
        Schema::create('daily_report_tankers', function (Blueprint $table) {
            $table->id();
            $table->date('report_date');
            $table->integer('count_tankers');
            $table->integer('count_tanker_under_maintenance');
            $table->integer('count_tanker_afkir');
            $table->integer('count_tanker_available');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_report_tankers');
    }
};
