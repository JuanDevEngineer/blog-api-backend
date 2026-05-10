<?php

declare(strict_types=1);

namespace App\Application\UseCases\Role;

use App\Domain\Entities\Role;
use App\Domain\Exceptions\RoleNotFoundException;
use App\Domain\Ports\Input\Role\FindRoleByIdPort;
use App\Domain\Ports\Output\RoleRepositoryPort;

class FindRoleByIdUseCase implements FindRoleByIdPort
{
    public function __construct(
        private readonly RoleRepositoryPort $roleRepository,
    ) {}

    public function execute(int $id): Role
    {
        $role = $this->roleRepository->findById($id);

        if ($role === null) {
            throw new RoleNotFoundException($id);
        }

        return $role;
    }
}
