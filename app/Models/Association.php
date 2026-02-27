<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Staircase;
use App\Models\SuiteType;

class Association extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'fiscal_code',
        'is_active',
    ];

    public function staircases()
    {
        return $this->hasMany(Staircase::class);
    }

    public function suiteTypes()
    {
        return $this->hasMany(SuiteType::class);
    }

    public function suites()
    {
        return $this->hasMany(Suite::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
