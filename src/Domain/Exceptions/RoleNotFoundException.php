<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

class RoleNotFoundException extends DomainException
{
    public function __construct(int $id)
    {
        parent::__construct("Role with id {$id} not found", 404);
    }
}
