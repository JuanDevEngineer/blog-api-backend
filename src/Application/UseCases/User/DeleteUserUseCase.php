<?php

declare(strict_types=1);

namespace App\Application\UseCases\User;

use App\Domain\Exceptions\UserNotFoundException;
use App\Domain\Ports\Input\User\DeleteUserPort;
use App\Domain\Ports\Output\UserRepositoryPort;

class DeleteUserUseCase implements DeleteUserPort
{
    public function __construct(
        private readonly UserRepositoryPort $userRepository,
    ) {}

    public function execute(int $id): void
    {
        if (!$this->userRepository->delete($id)) {
            throw new UserNotFoundException($id);
        }
    }
}
