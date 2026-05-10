<?php

declare(strict_types=1);

namespace App\Domain\Ports\Output;

use App\Domain\Entities\Blog;

interface BlogRepositoryPort
{
    public function findAll(): array;

    public function findById(int $id): ?Blog;

    public function create(Blog $blog): bool;

    public function update(Blog $blog): bool;

    public function delete(int $id): bool;
}
