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
        Schema::table('courses', function (Blueprint $table) {
            $table->foreignId('certificate_id')->nullable()->constrained('course_certificates')->onDelete('set null');
            $table->foreignId('industry_id')->nullable()->constrained('course_industries')->onDelete('set null');
            $table->foreignId('course_type_id')->nullable()->constrained('course_types')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['certificate_id']);
            $table->dropForeign(['industry_id']);
            $table->dropForeign(['course_type_id']);
            $table->dropColumn(['certificate_id', 'industry_id', 'course_type_id']);
        });
    }
};
