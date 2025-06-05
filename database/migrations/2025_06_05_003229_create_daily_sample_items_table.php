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
        Schema::create('daily_sample_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_sample_id')->constrained('daily_samples');
            $table->foreignId('product_id')->constrained('products');
            $table->decimal('dencity',5, 3);
            $table->integer('temperature');
            $table->boolean('nil_water');
            $table->decimal('water_volume', 6, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_sample_items');
    }
};
