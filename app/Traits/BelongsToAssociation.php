<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use App\Support\AssociationContext;

trait BelongsToAssociation
{
    protected static function bootBelongsToAssociation()
    {
        static::addGlobalScope('association', function (Builder $builder) {
            $associationId = app(AssociationContext::class)->get();

            if ($associationId) {
                $builder->where('association_id', $associationId);
            }
        });

        static::creating(function ($model) {
            $associationId = app(AssociationContext::class)->get();

            if ($associationId && empty($model->association_id)) {
                $model->association_id = $associationId;
            }
        });
    }
}