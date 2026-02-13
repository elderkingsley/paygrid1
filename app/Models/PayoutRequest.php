<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // Required for UUIDs
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayoutRequest extends Model
{
    use HasUuids;

    protected $fillable = [
        'organization_id',
        'requester_id',
        'amount',
        'beneficiary_name',
        'account_number',
        'bank_code',
        'bank_name',
        'reason',
        'proof_of_work_path',
        'status',
        'rejection_note',
        'approver_id',
        'approved_at',
        'disburser_id',
        'disbursed_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'disbursed_at' => 'datetime',
    ];

    // Relationships
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    /**
     * Internal Control: Ensure separation of duties.
     */
    public function canBeActionedBy($user): bool
    {
        // A user cannot approve or disburse their own request.
        return $this->requester_id !== $user->id;
    }

}
