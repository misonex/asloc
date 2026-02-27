<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToAssociation;

class Staircase extends Model
{
    use HasFactory, BelongsToAssociation;

    protected $fillable = [
        'association_id',
        'name',
    ];

    public function association()
    {
        return $this->belongsTo(Association::class);
    }

    public function suites()
    {
        return $this->hasMany(Suite::class);
    }
}
