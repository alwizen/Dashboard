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
        Schema::create('bill_fleets', function (Blueprint $table) {
            $table->id();
            $table->string('month'); // Januari, Februari, dst.
            $table->year('year')->default(date('Y'));
            $table->string('bill_name'); 
            $table->decimal('bill_value', 15, 2)->nullable();
            $table->string('progress')->nullable(); // BA / PR / PO / SA / PA
            $table->string('status')->nullable();  // Jika status PA -> done
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_fleets');
    }
};
