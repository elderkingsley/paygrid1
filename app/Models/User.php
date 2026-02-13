<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\BelongsToTenant;

class User extends Authenticatable
{
    use Notifiable, HasUuids, BelongsToTenant;

    protected $fillable = [
        'organization_id',
        'role',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'bvn',
        'nin',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // The Multi-Tenant Link
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function payoutRequests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PayoutRequest::class, 'requester_id');
    }
}
