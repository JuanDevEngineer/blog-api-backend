<?php

declare(strict_types=1);

namespace App\Domain\Entities;

class Role
{
    public function __construct(
        public readonly ?int    $id,
        public readonly string  $name,
        public readonly int     $state     = 1,
        public readonly ?string $createdAt = null,
        public readonly ?string $updatedAt = null,
    ) {}
}
