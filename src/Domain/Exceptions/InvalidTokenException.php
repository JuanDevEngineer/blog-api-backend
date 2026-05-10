<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

class InvalidTokenException extends DomainException
{
    public function __construct(string $reason = 'Token is invalid or expired')
    {
        parent::__construct($reason, 401);
    }
}
