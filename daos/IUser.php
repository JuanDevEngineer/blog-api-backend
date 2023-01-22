<?php

namespace Daos;

use Models\User;

interface IUser extends Repository
{
	public function register(User $user): array;
	
	public function create(User $user): array;
	
	public function findAll();
	
	public function findById($id);
	
	public function existUser(User $user): bool|array;
	
	public function findByEmail(User $user);
	
	public function update(User $user);
	
	public function delete($id);
}