<?php

declare(strict_types=1);

namespace App\Domain\Ports\Input\Blog;

interface DeleteBlogPort
{
    public function execute(int $id): void;
}
