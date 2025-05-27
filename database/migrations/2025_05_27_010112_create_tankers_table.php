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
        Schema::create('tankers', function (Blueprint $table) {
            $table->id();
            $table->string('nopol')->unique();
            $table->string('product');
            $table->string('capacity');
            $table->date('kir_expiry')->nullable();
            $table->date('kim_expiry')->nullable();
            $table->string('status')->default('available'); // available, in_use, under_maintenance
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tankers');
    }
};
