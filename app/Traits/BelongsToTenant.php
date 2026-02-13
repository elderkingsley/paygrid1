<?php

namespace App\Traits;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Model;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant()
    {
        static::addGlobalScope(new TenantScope);

        // Automatically assign organization_id when creating new records
        static::creating(function (Model $model) {
            if (auth()->check()) {
                $model->organization_id = auth()->user()->organization_id;
            }
        });
    }
}
