<?php

declare(strict_types=1);

namespace App\Domain\Ports\Output;

interface TokenServicePort
{
    public function generate(int $userId): string;

    public function verify(string $token): void;
}
