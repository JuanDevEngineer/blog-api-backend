<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

class CategoryNotFoundException extends DomainException
{
    public function __construct(int $id)
    {
        parent::__construct("Category with id {$id} not found", 404);
    }
}
