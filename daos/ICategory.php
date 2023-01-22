<?php

namespace Daos;

use Models\Category;

interface ICategory extends Repository
{
	public function create(Category $category): array;
	
	public function update(Category $category);
}