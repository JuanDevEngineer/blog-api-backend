<?php

declare(strict_types=1);

namespace App\Domain\Ports\Input\Role;

interface DeleteRolePort
{
    public function execute(int $id): void;
}
