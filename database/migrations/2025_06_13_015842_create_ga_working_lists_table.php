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
        Schema::create('ga_working_lists', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->integer('progres');
            $table->text('description')->nullable();
            $table->string('category');
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ga_working_lists');
    }
};
