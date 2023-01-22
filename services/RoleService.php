<?php

namespace Services;

use Daos\IRole;
use Daos\RoleDao;
use Models\Role;

class RoleService
{
  private RoleDao $roleDao;

  public function __construct(IRole $role)
  {
    $this->roleDao = $role;
  }

  public function create(Role $rol): array
  {
    return $this->roleDao->create($rol);
  }

  public function findAll(): array
  {
    return $this->roleDao->findAll();
  }

  public function findById($id)
  {
    return $this->roleDao->findById($id);
  }

  public function update(Role $rol): ?array
  {
    return $this->roleDao->update($rol);
  }

  public function delete($id): ?array
  {
    return $this->roleDao->delete($id);
  }
}
