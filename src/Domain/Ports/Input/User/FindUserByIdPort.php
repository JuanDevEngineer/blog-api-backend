<?php

declare(strict_types=1);

namespace App\Domain\Ports\Input\User;

use App\Domain\Entities\User;

interface FindUserByIdPort
{
    public function execute(int $id): User;
}
