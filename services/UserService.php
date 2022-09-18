<?php

namespace Services;

use Daos\UserDaoImpl;
use Models\User;

class UserService
{
  private $daoUser;

  public function __construct()
  {
    $this->daoUser = new UserDaoImpl();
  }

  public function create(User $user)
  {
    return $this->daoUser->create($user);
  }

  public function findAll()
  {
    return $this->daoUser->findAll();
  }

  public function findById($id)
  {
    return $this->daoUser->findById($id);
  }

  public function update(User $user)
  {
    return $this->daoUser->update($user);
  }

  public function delete($id)
  {
    return $this->daoUser->delete($id);
  }
}
