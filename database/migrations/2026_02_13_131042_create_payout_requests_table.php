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
    Schema::create('payout_requests', function (Blueprint $table) {
        $table->uuid('id')->primary();

        // Use foreignUuid because organizations.id is a UUID
        $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();

        // Requesters are also Users (who have UUIDs)
        $table->foreignUuid('requester_id')->constrained('users')->cascadeOnDelete();

        // Transaction Details
        $table->decimal('amount', 15, 2);
        $table->string('beneficiary_name');
        $table->string('account_number');
        $table->string('bank_code');
        $table->string('bank_name');
        $table->text('reason');
        $table->string('proof_of_work_path')->nullable();

        // Workflow State
        $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'disbursed'])->default('draft');
        $table->text('rejection_note')->nullable();

        // Audit Trail (Must all be UUIDs to match the Users table)
        $table->foreignUuid('approver_id')->nullable()->constrained('users');
        $table->timestamp('approved_at')->nullable();
        $table->foreignUuid('disburser_id')->nullable()->constrained('users');
        $table->timestamp('disbursed_at')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payout_requests');
    }
};
