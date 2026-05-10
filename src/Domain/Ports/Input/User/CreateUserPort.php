<?php

declare(strict_types=1);

namespace App\Domain\Ports\Input\User;

use App\Domain\Entities\User;

interface CreateUserPort
{
    public function execute(User $user): void;
}
