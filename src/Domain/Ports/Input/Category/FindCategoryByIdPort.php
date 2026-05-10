<?php

declare(strict_types=1);

namespace App\Domain\Ports\Input\Category;

use App\Domain\Entities\Category;

interface FindCategoryByIdPort
{
    public function execute(int $id): Category;
}
