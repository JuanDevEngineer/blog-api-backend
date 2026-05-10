<?php

declare(strict_types=1);

namespace App\Domain\Ports\Input\Auth;

interface LoginPort
{
    /** @return array{user: array{name: string, email: string, rol: string}, token: string} */
    public function execute(string $email, string $password): array;
}
