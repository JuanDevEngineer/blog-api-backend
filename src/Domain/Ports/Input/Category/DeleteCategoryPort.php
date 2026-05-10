<?php

declare(strict_types=1);

namespace App\Domain\Ports\Input\Category;

interface DeleteCategoryPort
{
    public function execute(int $id): void;
}
