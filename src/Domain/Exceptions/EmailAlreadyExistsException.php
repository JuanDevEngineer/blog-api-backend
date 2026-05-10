<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

class EmailAlreadyExistsException extends DomainException
{
    public function __construct(string $email)
    {
        parent::__construct("Email '{$email}' is already registered", 409);
    }
}
