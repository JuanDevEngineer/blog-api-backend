<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Application\UseCases\Auth\LoginUseCase;
use App\Application\UseCases\Auth\RegisterUseCase;
use App\Domain\Entities\User;
use App\Domain\Exceptions\EmailAlreadyExistsException;
use App\Domain\Exceptions\InvalidCredentialsException;
use App\Domain\Exceptions\ValidationException;

class AuthController extends AppController
{
    public function __construct(
        private readonly LoginUseCase    $loginUseCase,
        private readonly RegisterUseCase $registerUseCase,
    ) {}

    public function login(): void
    {
        $data = $this->request();

        if (empty($data['email']) || empty($data['password'])) {
            echo $this->methodBadRequest('Email and password are required');
            return;
        }

        try {
            $result = $this->loginUseCase->execute($data['email'], $data['password']);
            echo $this->methodOk('', $result);
        } catch (InvalidCredentialsException $e) {
            echo $this->methodUnauthorized($e->getMessage());
        } catch (ValidationException $e) {
            echo $this->methodBadRequest($e->getMessage());
        } catch (\Throwable) {
            echo $this->methodErrorServer('An unexpected error occurred');
        }
    }

    public function register(): void
    {
        $data = $this->request();

        $required = ['name', 'email', 'password', 'confirmPassword', 'numberPhone'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                echo $this->methodBadRequest("Field '{$field}' is required");
                return;
            }
        }

        if ($data['password'] !== $data['confirmPassword']) {
            echo $this->methodBadRequest('Passwords do not match');
            return;
        }

        $user = new User(
            id:          null,
            name:        $data['name'],
            email:       $data['email'],
            password:    $data['password'],
            numberPhone: $data['numberPhone'],
            roleId:      2,
        );

        try {
            $this->registerUseCase->execute($user);
            echo $this->methodCreated('User registered successfully');
        } catch (EmailAlreadyExistsException $e) {
            echo $this->methodConflict($e->getMessage());
        } catch (ValidationException $e) {
            echo $this->methodBadRequest($e->getMessage());
        } catch (\Throwable) {
            echo $this->methodErrorServer('An unexpected error occurred');
        }
    }
}
