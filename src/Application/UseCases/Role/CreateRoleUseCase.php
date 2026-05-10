<?php

declare(strict_types=1);

namespace App\Application\UseCases\Role;

use App\Domain\Entities\Role;
use App\Domain\Exceptions\ValidationException;
use App\Domain\Ports\Input\Role\CreateRolePort;
use App\Domain\Ports\Output\RoleRepositoryPort;

class CreateRoleUseCase implements CreateRolePort
{
    public function __construct(
        private readonly RoleRepositoryPort $roleRepository,
    ) {}

    public function execute(Role $role): void
    {
        if (empty(trim($role->name))) {
            throw new ValidationException('Role name is required');
        }

        $normalized = new Role(
            id:    null,
            name:  strtoupper(trim($role->name)),
            state: $role->state,
        );

        $this->roleRepository->create($normalized);
    }
}
