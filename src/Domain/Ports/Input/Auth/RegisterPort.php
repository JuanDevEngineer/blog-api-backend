<?php

declare(strict_types=1);

namespace App\Domain\Ports\Input\Auth;

use App\Domain\Entities\User;

interface RegisterPort
{
    public function execute(User $user): void;
}
