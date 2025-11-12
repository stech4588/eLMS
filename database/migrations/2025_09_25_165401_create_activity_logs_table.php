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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('activity_type'); // e.g., 'course_viewed', 'lesson_completed'
            $table->json('context')->nullable(); // Store additional context as JSON
            $table->timestamp('last_viewed_at')->nullable(); // Track last view for progress
            $table->timestamps();

            $table->unique(['user_id', 'course_id', 'activity_type']); // Ensure unique activity per user/course
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
