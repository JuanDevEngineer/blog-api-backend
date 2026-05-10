<?php

declare(strict_types=1);

namespace App\Domain\Ports\Output;

use App\Domain\Entities\Category;

interface CategoryRepositoryPort
{
    public function findAll(): array;

    public function findById(int $id): ?Category;

    public function nameExists(string $name): bool;

    public function create(Category $category): bool;

    public function update(Category $category): bool;

    public function delete(int $id): bool;
}
