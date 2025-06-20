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
        Schema::table('daily_report_tankers', function (Blueprint $table) {
            $table->unsignedInteger('total_capacity_available')->nullable()->after('count_tanker_available');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_report_tankers', function (Blueprint $table) {
            $table->dropColumn('total_capacity_available');
        });
    }
};
