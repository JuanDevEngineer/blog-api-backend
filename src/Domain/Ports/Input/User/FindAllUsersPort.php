<?php

declare(strict_types=1);

namespace App\Domain\Ports\Input\User;

interface FindAllUsersPort
{
    public function execute(): array;
}
