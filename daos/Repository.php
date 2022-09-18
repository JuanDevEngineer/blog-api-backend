<?php

namespace Daos;

interface Repository
{
  public function findAll();

  public function findById($id);

  public function delete($id);
}
