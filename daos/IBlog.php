<?php

namespace Daos;

use Models\Blog;

interface IBlog extends Repository
{
	public function create(Blog $blog): array;
	
	public function update(Blog $blog);
}