<?php

namespace Services;

use Daos\RoleDaoImpl;
use Models\Role;

class RoleService
{
  private $roleDao;

  public function __construct()
  {
    $this->roleDao = new RoleDaoImpl();
  }

  public function create(Role $rol)
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

  public function update(Role $rol)
  {
    return $this->roleDao->update($rol);
  }

  public function delete($id)
  {
    return $this->roleDao->delete($id);
  }
}
