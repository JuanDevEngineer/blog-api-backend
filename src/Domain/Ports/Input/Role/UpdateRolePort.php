<?php

declare(strict_types=1);

namespace App\Domain\Ports\Input\Role;

use App\Domain\Entities\Role;

interface UpdateRolePort
{
    public function execute(Role $role): void;
}
