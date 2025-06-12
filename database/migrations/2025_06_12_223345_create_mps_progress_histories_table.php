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
        Schema::create('mps_progress_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mps_working_list_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('progress'); // 0-100
            $table->text('note')->nullable();
            $table->date('progress_date')->useCurrent(); // Waktu input progress
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mps_progress_histories');
    }
};
