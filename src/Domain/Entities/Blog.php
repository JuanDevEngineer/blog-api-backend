<?php

declare(strict_types=1);

namespace App\Domain\Entities;

class Blog
{
    public function __construct(
        public readonly ?int    $id,
        public readonly int     $categoryId,
        public readonly string  $title,
        public readonly string  $slug,
        public readonly string  $textShort,
        public readonly string  $textLarge,
        public readonly string  $pathImage  = '',
        public readonly ?string $createdAt  = null,
        public readonly ?string $updatedAt  = null,
    ) {}
}
