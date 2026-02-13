<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        // Only apply the scope if we are NOT in the middle of a login check
        // and if the user is actually authenticated.
        if (app()->bound('auth') && Auth::hasUser()) {
            $user = Auth::user();

            // Safety check: only filter if the table actually has the column
            if (Schema::hasColumn($model->getTable(), 'organization_id')) {
                $builder->where($model->getTable() . '.organization_id', $user->organization_id);
            }
        }
    }
}
