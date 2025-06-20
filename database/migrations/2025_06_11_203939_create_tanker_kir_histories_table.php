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
        Schema::create('tanker_kir_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tanker_id')->constrained()->cascadeOnDelete();
            $table->date('expiry_date'); // tanggal berlaku
            $table->string('document')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanker_kir_histories');
    }
};
