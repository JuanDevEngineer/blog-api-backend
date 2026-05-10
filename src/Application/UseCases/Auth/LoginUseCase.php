<?php

declare(strict_types=1);

namespace App\Application\UseCases\Auth;

use App\Domain\Entities\User;
use App\Domain\Exceptions\InvalidCredentialsException;
use App\Domain\Exceptions\ValidationException;
use App\Domain\Ports\Input\Auth\LoginPort;
use App\Domain\Ports\Output\TokenServicePort;
use App\Domain\Ports\Output\UserRepositoryPort;

class LoginUseCase implements LoginPort
{
    public function __construct(
        private readonly UserRepositoryPort  $userRepository,
        private readonly TokenServicePort    $tokenService,
    ) {}

    public function execute(string $email, string $password): array
    {
        if (empty(trim($email)) || empty(trim($password))) {
            throw new ValidationException('Email and password are required');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException('Enter a valid email address');
        }

        $user = $this->userRepository->findByEmail($email);

        if ($user === null) {
            throw new InvalidCredentialsException();
        }

        if (!password_verify($password, $user->password)) {
            throw new InvalidCredentialsException();
        }

        return [
            'user'  => [
                'name'  => $user->name,
                'email' => $user->email,
                'rol'   => $user->roleName,
            ],
            'token' => $this->tokenService->generate($user->id),
        ];
    }
}
