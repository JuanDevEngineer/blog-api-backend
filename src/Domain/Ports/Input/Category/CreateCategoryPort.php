<?php

declare(strict_types=1);

namespace App\Domain\Ports\Input\Category;

use App\Domain\Entities\Category;

interface CreateCategoryPort
{
    public function execute(Category $category): void;
}
