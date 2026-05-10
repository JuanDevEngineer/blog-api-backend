<?php

declare(strict_types=1);

namespace App\Domain\Ports\Input\Role;

interface FindAllRolesPort
{
    public function execute(): array;
}
