<?php

namespace Daos;

use Models\Role;

interface IRole extends Repository
{
	public function create(Role $rol): array;
	public function update(Role $rol);
}