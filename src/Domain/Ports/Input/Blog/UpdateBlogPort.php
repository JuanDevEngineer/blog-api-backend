<?php

declare(strict_types=1);

namespace App\Domain\Ports\Input\Blog;

use App\Domain\Entities\Blog;

interface UpdateBlogPort
{
    public function execute(Blog $blog): void;
}
