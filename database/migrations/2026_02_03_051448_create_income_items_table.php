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
        Schema::create('income_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('working_paper_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rental_property_id')->nullable()->constrained()->cascadeOnDelete();
            $table->enum('work_type', ['wage', 'rental_property', 'sole_trader', 'bas', 'ctax', 'ttax', 'smsf']);
            $table->string('description');
            $table->decimal('amount', 15, 2);
            $table->enum('quarter', ['all', 'q1', 'q2', 'q3', 'q4'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income_items');
    }
};
