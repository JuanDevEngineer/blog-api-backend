<?php

declare(strict_types=1);

namespace App\Application\UseCases\Blog;

use App\Domain\Entities\Blog;
use App\Domain\Exceptions\CategoryNotFoundException;
use App\Domain\Exceptions\ValidationException;
use App\Domain\Ports\Input\Blog\CreateBlogPort;
use App\Domain\Ports\Output\BlogRepositoryPort;
use App\Domain\Ports\Output\CategoryRepositoryPort;

class CreateBlogUseCase implements CreateBlogPort
{
    public function __construct(
        private readonly BlogRepositoryPort     $blogRepository,
        private readonly CategoryRepositoryPort $categoryRepository,
    ) {}

    public function execute(Blog $blog): void
    {
        if (empty(trim($blog->title))) {
            throw new ValidationException('Title is required');
        }

        if (empty(trim($blog->slug))) {
            throw new ValidationException('Slug is required');
        }

        if (empty(trim($blog->textShort))) {
            throw new ValidationException('Short text is required');
        }

        if (empty(trim($blog->textLarge))) {
            throw new ValidationException('Large text is required');
        }

        if ($this->categoryRepository->findById($blog->categoryId) === null) {
            throw new CategoryNotFoundException($blog->categoryId);
        }

        $this->blogRepository->create($blog);
    }
}
