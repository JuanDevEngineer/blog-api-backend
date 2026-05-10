<?php

declare(strict_types=1);

namespace App\Domain\Ports\Input\Blog;

use App\Domain\Entities\Blog;

interface FindBlogByIdPort
{
    public function execute(int $id): Blog;
}
