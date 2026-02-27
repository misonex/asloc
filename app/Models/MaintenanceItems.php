<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToAssociation;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaintenanceItems extends Model
{
    use HasFactory, BelongsToAssociation;
}
