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
        Schema::create('criteria_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('division_id')->constrained()->onDelete('cascade');
            $table->string('field_name');
            $table->enum('field_type', [
                'text',
                'number',
                'percentage',
                'date',
                'datetime',
                'status',
                'select',
                'textarea',
                'boolean'
            ]);
            $table->json('field_options')->nullable(); // For select options, validation rules, etc
            $table->boolean('is_required')->default(false);
            $table->integer('display_order')->default(0);
            $table->string('unit')->nullable(); // For percentage (%), unit (KL), etc
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['division_id', 'field_name']);
            $table->index(['division_id', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criteria_templates');
    }
};
