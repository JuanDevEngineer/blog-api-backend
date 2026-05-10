<?php

declare(strict_types=1);

namespace App\Application\UseCases\Role;

use App\Domain\Ports\Input\Role\FindAllRolesPort;
use App\Domain\Ports\Output\RoleRepositoryPort;

class FindAllRolesUseCase implements FindAllRolesPort
{
    public function __construct(
        private readonly RoleRepositoryPort $roleRepository,
    ) {}

    public function execute(): array
    {
        return $this->roleRepository->findAll();
    }
}
