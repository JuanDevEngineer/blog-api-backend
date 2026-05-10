<?php

declare(strict_types=1);

namespace App\Domain\Ports\Output;

use App\Domain\Entities\User;

interface UserRepositoryPort
{
    public function findAll(): array;

    public function findById(int $id): ?User;

    /** Busca por email e incluye el password hash para poder verificarlo */
    public function findByEmail(string $email): ?User;

    public function emailExists(string $email): bool;

    public function create(User $user): bool;

    public function update(User $user): bool;

    public function delete(int $id): bool;
}
