<?php

declare(strict_types=1);

namespace App\Domain\Ports\Input\Category;

interface FindAllCategoriesPort
{
    public function execute(): array;
}
