<?php

namespace Services;

use Daos\IBlog;
use Models\Blog;
use Daos\BlogDao;

class BlogService
{
  private BlogDao $blogDao;

  public function __construct(IBlog $blog)
  {
    $this->blogDao = $blog;
  }

  public function create(Blog $blog): array
  {
    return $this->blogDao->create($blog);
  }

  public function findAll(): bool|array|null
  {
    return $this->blogDao->findAll();
  }

  public function findById($id)
  {
    return $this->blogDao->findById($id);
  }

  public function update(Blog $blog): ?array
  {
    return $this->blogDao->update($blog);
  }

  public function delete($id): ?array
  {
    return $this->blogDao->delete($id);
  }
}
