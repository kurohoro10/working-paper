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
        Schema::create('rental_properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('working_paper_id')->constrained()->cascadeOnDelete();
            $table->string('address_label');
            $table->decimal('ownership_percentage', 5, 2)->nullable();
            $table->date('rented_from')->nullable();
            $table->date('rented_to')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_properties');
    }
};
