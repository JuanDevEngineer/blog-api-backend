<?php

declare(strict_types=1);

namespace App\Application\UseCases\Category;

use App\Domain\Exceptions\CategoryNotFoundException;
use App\Domain\Ports\Input\Category\DeleteCategoryPort;
use App\Domain\Ports\Output\CategoryRepositoryPort;

class DeleteCategoryUseCase implements DeleteCategoryPort
{
    public function __construct(
        private readonly CategoryRepositoryPort $categoryRepository,
    ) {}

    public function execute(int $id): void
    {
        if (!$this->categoryRepository->delete($id)) {
            throw new CategoryNotFoundException($id);
        }
    }
}
