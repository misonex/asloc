<?php

namespace App\Support;

class AssociationContext
{
    protected ?int $associationId = null;

    public function set(?int $associationId): void
    {
        $this->associationId = $associationId;
    }

    public function get(): ?int
    {
        return $this->associationId;
    }

    public function has(): bool
    {
        return ! is_null($this->associationId);
    }

    public function clear(): void
    {
        $this->associationId = null;
    }
}