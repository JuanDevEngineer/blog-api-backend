<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Application\UseCases\Category\CreateCategoryUseCase;
use App\Application\UseCases\Category\DeleteCategoryUseCase;
use App\Application\UseCases\Category\FindAllCategoriesUseCase;
use App\Application\UseCases\Category\FindCategoryByIdUseCase;
use App\Application\UseCases\Category\UpdateCategoryUseCase;
use App\Domain\Entities\Category;
use App\Domain\Exceptions\CategoryAlreadyExistsException;
use App\Domain\Exceptions\CategoryNotFoundException;
use App\Domain\Exceptions\ValidationException;

class CategoryController extends AppController
{
    public function __construct(
        private readonly CreateCategoryUseCase   $createCategory,
        private readonly FindAllCategoriesUseCase $findAllCategories,
        private readonly FindCategoryByIdUseCase $findCategoryById,
        private readonly UpdateCategoryUseCase   $updateCategory,
        private readonly DeleteCategoryUseCase   $deleteCategory,
    ) {}

    public function findAll(): void
    {
        echo $this->methodOk('', $this->findAllCategories->execute());
    }

    public function findById(int $id): void
    {
        try {
            $category = $this->findCategoryById->execute($id);
            echo $this->methodOk('', $this->categoryToArray($category));
        } catch (CategoryNotFoundException $e) {
            echo $this->methodNotFound($e->getMessage());
        }
    }

    public function create(): void
    {
        $data = $this->request();

        if (empty($data['name'])) {
            echo $this->methodBadRequest('Category name is required');
            return;
        }

        try {
            $this->createCategory->execute(new Category(id: null, name: $data['name']));
            echo $this->methodCreated('Category created successfully');
        } catch (CategoryAlreadyExistsException $e) {
            echo $this->methodConflict($e->getMessage());
        } catch (ValidationException $e) {
            echo $this->methodBadRequest($e->getMessage());
        } catch (\Throwable) {
            echo $this->methodErrorServer('An unexpected error occurred');
        }
    }

    public function update(int $id): void
    {
        $data = $this->request();

        if (empty($data['name'])) {
            echo $this->methodBadRequest('Category name is required');
            return;
        }

        try {
            $this->updateCategory->execute(new Category(id: $id, name: $data['name']));
            echo $this->methodOk('Category updated successfully');
        } catch (CategoryNotFoundException $e) {
            echo $this->methodNotFound($e->getMessage());
        } catch (ValidationException $e) {
            echo $this->methodBadRequest($e->getMessage());
        } catch (\Throwable) {
            echo $this->methodErrorServer('An unexpected error occurred');
        }
    }

    public function delete(int $id): void
    {
        try {
            $this->deleteCategory->execute($id);
            echo $this->methodOk('Category deleted successfully');
        } catch (CategoryNotFoundException $e) {
            echo $this->methodNotFound($e->getMessage());
        } catch (\Throwable) {
            echo $this->methodErrorServer('An unexpected error occurred');
        }
    }

    private function categoryToArray(Category $category): array
    {
        return [
            'id'         => $category->id,
            'name'       => $category->name,
            'state'      => $category->state,
            'created_at' => $category->createdAt,
            'updated_at' => $category->updatedAt,
        ];
    }
}
