<?php

declare(strict_types=1);

namespace App\Application\UseCases\Category;

use App\Domain\Entities\Category;
use App\Domain\Exceptions\CategoryNotFoundException;
use App\Domain\Ports\Input\Category\FindCategoryByIdPort;
use App\Domain\Ports\Output\CategoryRepositoryPort;

class FindCategoryByIdUseCase implements FindCategoryByIdPort
{
    public function __construct(
        private readonly CategoryRepositoryPort $categoryRepository,
    ) {}

    public function execute(int $id): Category
    {
        $category = $this->categoryRepository->findById($id);

        if ($category === null) {
            throw new CategoryNotFoundException($id);
        }

        return $category;
    }
}
