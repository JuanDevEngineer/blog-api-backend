<?php

declare(strict_types=1);

namespace App\Domain\Ports\Input\User;

interface DeleteUserPort
{
    public function execute(int $id): void;
}
