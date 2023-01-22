<?php

namespace Services;

use Daos\CategoryDao;
use Daos\ICategory;
use Models\Category;

class CategoryService
{
  private CategoryDao $categoryDao;

  public function __construct(ICategory $category)
  {
    $this->categoryDao = $category;
  }

  public function create(Category $category): array
  {
    return $this->categoryDao->create($category);
  }

  public function findAll(): array
  {
    return $this->categoryDao->findAll();
  }

  public function findById($id)
  {
    return $this->categoryDao->findById($id);
  }

  public function update(Category $category): ?array
  {
    return $this->categoryDao->update($category);
  }

  public function delete($id): ?array
  {
    return $this->categoryDao->delete($id);
  }
}
