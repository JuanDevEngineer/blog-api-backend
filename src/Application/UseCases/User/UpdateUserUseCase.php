<?php

declare(strict_types=1);

namespace App\Application\UseCases\User;

use App\Domain\Entities\User;
use App\Domain\Exceptions\UserNotFoundException;
use App\Domain\Exceptions\ValidationException;
use App\Domain\Ports\Input\User\UpdateUserPort;
use App\Domain\Ports\Output\UserRepositoryPort;

class UpdateUserUseCase implements UpdateUserPort
{
    public function __construct(
        private readonly UserRepositoryPort $userRepository,
    ) {}

    public function execute(User $user): void
    {
        if (empty(trim($user->name))) {
            throw new ValidationException('Name is required');
        }

        if (strlen($user->numberPhone) < 10) {
            throw new ValidationException('Phone number must be at least 10 digits');
        }

        if ($user->roleId < 1) {
            throw new ValidationException('A valid role is required');
        }

        if (!$this->userRepository->update($user)) {
            throw new UserNotFoundException($user->id);
        }
    }
}
