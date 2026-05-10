<?php

declare(strict_types=1);

namespace App\Application\UseCases\Category;

use App\Domain\Entities\Category;
use App\Domain\Exceptions\CategoryNotFoundException;
use App\Domain\Exceptions\ValidationException;
use App\Domain\Ports\Input\Category\UpdateCategoryPort;
use App\Domain\Ports\Output\CategoryRepositoryPort;

class UpdateCategoryUseCase implements UpdateCategoryPort
{
    public function __construct(
        private readonly CategoryRepositoryPort $categoryRepository,
    ) {}

    public function execute(Category $category): void
    {
        if (empty(trim($category->name))) {
            throw new ValidationException('Category name is required');
        }

        if (!$this->categoryRepository->update($category)) {
            throw new CategoryNotFoundException($category->id);
        }
    }
}
