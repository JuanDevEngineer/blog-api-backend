<?php

declare(strict_types=1);

namespace App\Application\UseCases\Category;

use App\Domain\Entities\Category;
use App\Domain\Exceptions\CategoryAlreadyExistsException;
use App\Domain\Exceptions\ValidationException;
use App\Domain\Ports\Input\Category\CreateCategoryPort;
use App\Domain\Ports\Output\CategoryRepositoryPort;

class CreateCategoryUseCase implements CreateCategoryPort
{
    public function __construct(
        private readonly CategoryRepositoryPort $categoryRepository,
    ) {}

    public function execute(Category $category): void
    {
        if (empty(trim($category->name))) {
            throw new ValidationException('Category name is required');
        }

        if ($this->categoryRepository->nameExists(strtolower($category->name))) {
            throw new CategoryAlreadyExistsException($category->name);
        }

        $this->categoryRepository->create($category);
    }
}
