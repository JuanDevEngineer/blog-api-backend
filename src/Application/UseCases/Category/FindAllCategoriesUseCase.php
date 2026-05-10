<?php

declare(strict_types=1);

namespace App\Application\UseCases\Category;

use App\Domain\Ports\Input\Category\FindAllCategoriesPort;
use App\Domain\Ports\Output\CategoryRepositoryPort;

class FindAllCategoriesUseCase implements FindAllCategoriesPort
{
    public function __construct(
        private readonly CategoryRepositoryPort $categoryRepository,
    ) {}

    public function execute(): array
    {
        return $this->categoryRepository->findAll();
    }
}
