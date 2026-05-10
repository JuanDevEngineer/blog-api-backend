<?php

declare(strict_types=1);

namespace App\Domain\Ports\Output;

use App\Domain\Entities\Role;

interface RoleRepositoryPort
{
    public function findAll(): array;

    public function findById(int $id): ?Role;

    public function create(Role $role): bool;

    public function update(Role $role): bool;

    public function delete(int $id): bool;
}
