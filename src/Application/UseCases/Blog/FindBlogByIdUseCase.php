<?php

declare(strict_types=1);

namespace App\Application\UseCases\Blog;

use App\Domain\Entities\Blog;
use App\Domain\Exceptions\BlogNotFoundException;
use App\Domain\Ports\Input\Blog\FindBlogByIdPort;
use App\Domain\Ports\Output\BlogRepositoryPort;

class FindBlogByIdUseCase implements FindBlogByIdPort
{
    public function __construct(
        private readonly BlogRepositoryPort $blogRepository,
    ) {}

    public function execute(int $id): Blog
    {
        $blog = $this->blogRepository->findById($id);

        if ($blog === null) {
            throw new BlogNotFoundException($id);
        }

        return $blog;
    }
}
