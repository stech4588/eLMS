<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'billing_month')) {
                $table->date('billing_month')->nullable()->after('paid_at');
            }
            if (!Schema::hasColumn('invoices', 'due_date')) {
                $table->date('due_date')->nullable()->after('billing_month');
            }
            if (!Schema::hasColumn('invoices', 'status')) {
                $table->enum('status', ['unpaid', 'paid', 'overdue'])->default('unpaid')->after('due_date');
            }
            if (!Schema::hasColumn('invoices', 'notes')) {
                $table->text('notes')->nullable()->after('status');
            }
            if (!Schema::hasColumn('invoices', 'reminder_count')) {
                $table->unsignedInteger('reminder_count')->default(0)->after('notes');
            }
            if (!Schema::hasColumn('invoices', 'last_reminded_at')) {
                $table->timestamp('last_reminded_at')->nullable()->after('reminder_count');
            }
        });

        Schema::create('subscription_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->enum('state', ['active', 'pending', 'suspended', 'suspended_manual'])->default('pending');
            $table->timestamp('last_payment_date')->nullable();
            $table->foreignId('last_invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('subscription_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('monthly_due_day')->default(5);
            $table->json('reminder_offsets')->nullable();
            $table->unsignedTinyInteger('grace_period_days')->default(3);
            $table->timestamps();
        });

        Schema::create('suspension_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            $table->string('reason')->nullable();
            $table->timestamp('suspended_at');
            $table->timestamp('reactivated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suspension_logs');
        Schema::dropIfExists('subscription_settings');
        Schema::dropIfExists('subscription_statuses');

        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'last_reminded_at')) {
                $table->dropColumn('last_reminded_at');
            }
            if (Schema::hasColumn('invoices', 'reminder_count')) {
                $table->dropColumn('reminder_count');
            }
            if (Schema::hasColumn('invoices', 'notes')) {
                $table->dropColumn('notes');
            }
            if (Schema::hasColumn('invoices', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('invoices', 'due_date')) {
                $table->dropColumn('due_date');
            }
            if (Schema::hasColumn('invoices', 'billing_month')) {
                $table->dropColumn('billing_month');
            }
        });
    }
};

