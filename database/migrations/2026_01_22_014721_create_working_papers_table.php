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
        Schema::create('working_papers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('service')->nullable();
            $table->string('job_reference')->unique();
            $table->json('work_types')->nullable();
            $table->softDeletes();
            $table->enum('status', ['draft', 'sent', 'reviewed', 'returned', 'finalised']);
            $table->string('share_token')->unique()->nullable();
            $table->timestamp('share_token_expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('working_papers');
    }
};
