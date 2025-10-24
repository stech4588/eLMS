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
        Schema::table('prompts', function (Blueprint $table) {
            $table->enum('trigger_condition', ['daily', 'weekly'])->change();
            $table->integer('times_per_day')->nullable()->default(1)->after('frequency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prompts', function (Blueprint $table) {
            $table->string('trigger_condition')->change();
            $table->dropColumn('times_per_day');
        });
    }
};
