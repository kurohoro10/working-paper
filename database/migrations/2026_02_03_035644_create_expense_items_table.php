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
        Schema::create('expense_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('working_paper_id')->constrained()->cascadeOnDelete();
            $table->enum('work_type', ['wage', 'rental_property', 'sole_trader', 'bas', 'ctax', 'ttax', 'smsf']);
            $table->foreignId('rental_property_id')->nullable()->constrained()->cascadeOnDelete();
            $table->char('type', 1)->nullable();
            $table->string('description');
            $table->decimal('amount_inc_gst', 15, 2)->nullable();
            $table->decimal('gst_amount', 15, 2)->nullable();
            $table->decimal('net_ex_gst', 15, 2)->nullable();
            $table->enum('quarter', ['all', 'q1', 'q2', 'q3', 'q4'])->nullable();
            $table->text('client_comment');
            $table->text('internal_comment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_items');
    }
};
