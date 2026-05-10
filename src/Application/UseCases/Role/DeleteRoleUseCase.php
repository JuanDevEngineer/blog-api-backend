<?php

declare(strict_types=1);

namespace App\Application\UseCases\Role;

use App\Domain\Exceptions\RoleNotFoundException;
use App\Domain\Ports\Input\Role\DeleteRolePort;
use App\Domain\Ports\Output\RoleRepositoryPort;

class DeleteRoleUseCase implements DeleteRolePort
{
    public function __construct(
        private readonly RoleRepositoryPort $roleRepository,
    ) {}

    public function execute(int $id): void
    {
        if (!$this->roleRepository->delete($id)) {
            throw new RoleNotFoundException($id);
        }
    }
}
