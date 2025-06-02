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
        Schema::create('mps_working_list_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mps_working_list_id')
                ->constrained('mps_working_lists')
                ->onDelete('cascade');
            $table->integer('progres');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mps_working_list_histories');
    }
};
