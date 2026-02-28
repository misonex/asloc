<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssociationRequest extends Model
{
    protected $fillable = [
        'association_name',
        'association_address',
        'fiscal_code',
        'manager_name',
        'manager_email',
        'metadata',
        'status',
        'rejection_reason',
        'approved_at',
        'processed_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'approved_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    public function approve(): void
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);
    }

    public function reject(string $reason): void
    {
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ]);
    }

    public function markAsProcessed(): void
    {
        $this->update([
            'status' => 'processed',
            'processed_at' => now(),
        ]);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function tokens()
    {
        return $this->hasMany(AssociationRequestToken::class);
    }
}