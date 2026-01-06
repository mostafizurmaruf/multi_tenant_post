<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    protected static function booted()
    {
        static::addGlobalScope('tenant', function (Builder $builder) {
            if (app()->has('tenant')) {
                $builder->where(
                    $builder->getModel()->getTable() . '.tenant_id',
                    app('tenant')->id
                );
            }
        });

        static::creating(function ($model) {
            if (app()->has('tenant')) {
                $model->tenant_id = app('tenant')->id;
            }
        });
    }
}
