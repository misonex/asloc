<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssociationRequestToken extends Model
{
    protected $fillable = [
        'association_request_id',
        'token',
        'expires_at',
        'used_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function request()
    {
        return $this->belongsTo(AssociationRequest::class);
    }

    public function isValid(): bool
    {
        return ! $this->used_at && now()->lt($this->expires_at);
    }

    public function markAsUsed(): void
    {
        $this->update([
            'used_at' => now(),
        ]);
    }
}
