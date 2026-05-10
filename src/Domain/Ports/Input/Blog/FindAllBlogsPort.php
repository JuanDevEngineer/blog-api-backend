<?php

declare(strict_types=1);

namespace App\Domain\Ports\Input\Blog;

interface FindAllBlogsPort
{
    public function execute(): array;
}
