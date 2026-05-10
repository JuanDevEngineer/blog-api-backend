<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

class BlogNotFoundException extends DomainException
{
    public function __construct(int $id)
    {
        parent::__construct("Blog with id {$id} not found", 404);
    }
}
