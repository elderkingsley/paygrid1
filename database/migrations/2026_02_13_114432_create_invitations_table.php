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
    Schema::create('invitations', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->foreignUuId('organization_id')->constrained()->cascadeOnDelete();
        $table->string('email');
        $table->enum('role', ['requester', 'approver', 'disburser']);
        $table->string('token')->unique();
        $table->timestamp('expires_at');
        $table->timestamps();

        // Ensure an email isn't invited twice to the same org at the same time
        $table->unique(['organization_id', 'email']);
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
