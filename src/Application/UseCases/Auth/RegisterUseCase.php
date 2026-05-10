<?php

declare(strict_types=1);

namespace App\Application\UseCases\Auth;

use App\Domain\Entities\User;
use App\Domain\Exceptions\EmailAlreadyExistsException;
use App\Domain\Exceptions\ValidationException;
use App\Domain\Ports\Input\Auth\RegisterPort;
use App\Domain\Ports\Output\UserRepositoryPort;

class RegisterUseCase implements RegisterPort
{
    public function __construct(
        private readonly UserRepositoryPort $userRepository,
    ) {}

    public function execute(User $user): void
    {
        if (empty(trim($user->name))) {
            throw new ValidationException('Name is required');
        }

        if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException('Enter a valid email address');
        }

        if (strlen($user->password) < 8) {
            throw new ValidationException('Password must be at least 8 characters');
        }

        if (strlen($user->numberPhone) < 10) {
            throw new ValidationException('Phone number must be at least 10 digits');
        }

        if ($this->userRepository->emailExists($user->email)) {
            throw new EmailAlreadyExistsException($user->email);
        }

        $hashed = new User(
            id:          null,
            name:        $user->name,
            email:       $user->email,
            password:    password_hash($user->password, PASSWORD_BCRYPT, ['cost' => 12]),
            numberPhone: $user->numberPhone,
            roleId:      $user->roleId,
        );

        $this->userRepository->create($hashed);
    }
}
