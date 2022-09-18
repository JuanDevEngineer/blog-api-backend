<?php

namespace Services;

use Models\Blog;
use Daos\BlogDaoImpl;

class BlogService
{
  private $blogDao;

  public function __construct()
  {
    $this->blogDao = new BlogDaoImpl();
  }

  public function create(Blog $blog)
  {
    return $this->blogDao->create($blog);
  }

  public function findAll()
  {
    return $this->blogDao->findAll();
  }

  public function findById($id)
  {
    return $this->blogDao->findById($id);
  }

  public function update(Blog $blog)
  {
    return $this->blogDao->update($blog);
  }

  public function delete($id)
  {
    return $this->blogDao->delete($id);
  }
}
