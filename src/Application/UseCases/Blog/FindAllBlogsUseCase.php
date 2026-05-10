<?php

declare(strict_types=1);

namespace App\Application\UseCases\Blog;

use App\Domain\Ports\Input\Blog\FindAllBlogsPort;
use App\Domain\Ports\Output\BlogRepositoryPort;

class FindAllBlogsUseCase implements FindAllBlogsPort
{
    public function __construct(
        private readonly BlogRepositoryPort $blogRepository,
    ) {}

    public function execute(): array
    {
        return $this->blogRepository->findAll();
    }
}
