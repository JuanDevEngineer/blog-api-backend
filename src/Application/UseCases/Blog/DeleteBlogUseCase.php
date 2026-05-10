<?php

declare(strict_types=1);

namespace App\Application\UseCases\Blog;

use App\Domain\Exceptions\BlogNotFoundException;
use App\Domain\Ports\Input\Blog\DeleteBlogPort;
use App\Domain\Ports\Output\BlogRepositoryPort;

class DeleteBlogUseCase implements DeleteBlogPort
{
    public function __construct(
        private readonly BlogRepositoryPort $blogRepository,
    ) {}

    public function execute(int $id): void
    {
        if (!$this->blogRepository->delete($id)) {
            throw new BlogNotFoundException($id);
        }
    }
}
