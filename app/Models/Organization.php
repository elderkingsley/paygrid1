<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class Organization extends Model
{
    use HasUuids;

    protected $fillable = ['name', 'slug'];

    protected static function booted()
    {
        static::creating(function ($organization) {
            if (empty($organization->slug)) {
                $organization->slug = Str::slug($organization->name) . '-' . Str::random(5);
            }
        });
    }
}
