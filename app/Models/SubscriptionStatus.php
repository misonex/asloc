<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToAssociation;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionStatus extends Model
{
    use HasFactory, BelongsToAssociation;
}
