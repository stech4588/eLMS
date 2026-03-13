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
        Schema::create('community_post_poll_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('community_post_poll_id')->constrained()->onDelete('cascade');
            $table->foreignId('community_post_poll_option_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'community_post_poll_id', 'community_post_poll_option_id'], 'user_poll_option_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_post_poll_votes');
    }
};
