<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

class UserNotFoundException extends DomainException
{
    public function __construct(int $id)
    {
        parent::__construct("User with id {$id} not found", 404);
    }
}
