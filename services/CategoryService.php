<?php

namespace Services;

use Daos\CategoryDaoImpl;
use Models\Category;

class CategoryService
{
  private $categoryDao;

  public function __construct()
  {
    $this->categoryDao = new CategoryDaoImpl();
  }

  public function create(Category $category)
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

  public function update(Category $category)
  {
    return $this->categoryDao->update($category);
  }

  public function delete($id)
  {
    return $this->categoryDao->delete($id);
  }
}
