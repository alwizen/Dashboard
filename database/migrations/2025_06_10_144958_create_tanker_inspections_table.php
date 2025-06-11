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
        Schema::create('tanker_inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tanker_id')->constrained()->onDelete('cascade');
            $table->date('inspection_date');
            $table->enum('comp_1_status', ['kedap', 'tidak_kedap'])->nullable();
            $table->enum('comp_2_status', ['kedap', 'tidak_kedap'])->nullable();
            $table->enum('comp_3_status', ['kedap', 'tidak_kedap'])->nullable();
            $table->enum('comp_4_status', ['kedap', 'tidak_kedap'])->nullable();
            $table->enum('comp_5_status', ['kedap', 'tidak_kedap'])->nullable();
            $table->enum('overall_status', ['kedap', 'tidak_kedap']);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanker_inspections');
    }
};
