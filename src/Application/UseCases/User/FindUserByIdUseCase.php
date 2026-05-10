<?php

declare(strict_types=1);

namespace App\Application\UseCases\User;

use App\Domain\Entities\User;
use App\Domain\Exceptions\UserNotFoundException;
use App\Domain\Ports\Input\User\FindUserByIdPort;
use App\Domain\Ports\Output\UserRepositoryPort;

class FindUserByIdUseCase implements FindUserByIdPort
{
    public function __construct(
        private readonly UserRepositoryPort $userRepository,
    ) {}

    public function execute(int $id): User
    {
        $user = $this->userRepository->findById($id);

        if ($user === null) {
            throw new UserNotFoundException($id);
        }

        return $user;
    }
}
