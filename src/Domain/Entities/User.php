<?php

declare(strict_types=1);

namespace App\Domain\Entities;

class User
{
    public function __construct(
        public readonly ?int    $id,
        public readonly string  $name,
        public readonly string  $email,
        public readonly string  $password,
        public readonly string  $numberPhone,
        public readonly int     $roleId     = 2,
        public readonly int     $state      = 1,
        public readonly ?string $roleName   = null,
        public readonly ?string $createdAt  = null,
        public readonly ?string $updatedAt  = null,
    ) {}
}
