<?php

declare(strict_types=1);

namespace App\Application\UseCases\Role;

use App\Domain\Entities\Role;
use App\Domain\Exceptions\RoleNotFoundException;
use App\Domain\Exceptions\ValidationException;
use App\Domain\Ports\Input\Role\UpdateRolePort;
use App\Domain\Ports\Output\RoleRepositoryPort;

class UpdateRoleUseCase implements UpdateRolePort
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
            id:    $role->id,
            name:  strtoupper(trim($role->name)),
            state: $role->state,
        );

        if (!$this->roleRepository->update($normalized)) {
            throw new RoleNotFoundException($role->id);
        }
    }
}
