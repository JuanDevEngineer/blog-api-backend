<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

class ValidationException extends DomainException
{
    public function __construct(string $message)
    {
        parent::__construct($message, 400);
    }
}
