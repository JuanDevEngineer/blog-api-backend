<?php

declare(strict_types=1);

namespace App\Application\UseCases\User;

use App\Domain\Ports\Input\User\FindAllUsersPort;
use App\Domain\Ports\Output\UserRepositoryPort;

class FindAllUsersUseCase implements FindAllUsersPort
{
    public function __construct(
        private readonly UserRepositoryPort $userRepository,
    ) {}

    public function execute(): array
    {
        return $this->userRepository->findAll();
    }
}
