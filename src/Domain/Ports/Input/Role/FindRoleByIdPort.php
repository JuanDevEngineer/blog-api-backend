<?php

declare(strict_types=1);

namespace App\Domain\Ports\Input\Role;

use App\Domain\Entities\Role;

interface FindRoleByIdPort
{
    public function execute(int $id): Role;
}
