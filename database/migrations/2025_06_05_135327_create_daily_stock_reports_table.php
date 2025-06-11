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
        Schema::create('daily_stock_reports', function (Blueprint $table) {
            $table->id();
            $table->date('report_date');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('tank_number');

            // Safe Capacity
            $table->float('safe_cap_level')->nullable();
            $table->float('safe_cap_volume')->nullable();

            // Opening Stock
            $table->float('opening_stock_level')->nullable();
            $table->float('opening_stock_volume')->nullable();

            // Current Stock
            $table->float('current_stock_level')->nullable();
            $table->float('current_stock_volume')->nullable();

            // Air Level
            $table->float('current_air_level')->nullable();
            $table->float('current_air_volume')->nullable();

            // Dead Stock
            $table->float('dead_stock')->nullable();

            // Pump Stock
            $table->float('pump_stock')->nullable();

            // Ullage
            $table->float('ullage')->nullable();

            // Daily Draw Total (DDT) and Coverage Days (CD)
            $table->float('ddt')->nullable();
            $table->float('cd')->nullable();

            // Floating Tegak & Stafle Moss
            $table->float('floating_tegak')->nullable();
            $table->float('stafle_moss')->nullable();

            // Supply Info
            $table->float('next_supply')->nullable();
            $table->float('receipt')->nullable(); // Penerimaan
            $table->float('actual_throughput')->nullable();

            // Losses
            $table->float('working_loss_liter')->nullable();
            $table->float('working_loss_percent')->nullable();

            // Tank Decrease
            $table->float('tank_decrease')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_stock_reports');
    }
};
