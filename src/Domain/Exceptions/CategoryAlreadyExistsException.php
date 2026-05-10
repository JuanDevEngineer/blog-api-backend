<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

class CategoryAlreadyExistsException extends DomainException
{
    public function __construct(string $name)
    {
        parent::__construct("Category '{$name}' already exists", 409);
    }
}
