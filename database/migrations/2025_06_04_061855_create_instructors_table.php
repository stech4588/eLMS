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
        Schema::create('instructors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('linkedin_url')->nullable();
            $table->string('followers')->nullable(); // To store ranges like "0-1,000"
            $table->text('linkedin_programs')->nullable(); // To store selected programs, potentially as a comma-separated string or JSON
            $table->string('teaching_language')->nullable(); // To store the language the instructor will teach in
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructors');
    }
};
