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
        Schema::create('mps_working_lists', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->integer('progres');
            $table->text('description')->nullable();
            $table->foreignId('category_id')
                ->constrained('mps_working_list_categories')
                ->onDelete('cascade');
            $table->date('start_date')->nullable();
            $table->string('status');
            $table->dateTime('due_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mps_working_lists');
    }
};
