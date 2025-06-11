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
        Schema::table('tankers', function (Blueprint $table) {
            $table->foreignId('transportir_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('comp')->nullable();
            $table->string('merk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tankers', function (Blueprint $table) {
            $table->dropColumn('transportir_id');
            $table->dropColumn('comp');
            $table->dropColumn('merk');
        });
    }
};
