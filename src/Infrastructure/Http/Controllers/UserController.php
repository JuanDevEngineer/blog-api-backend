<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Application\UseCases\User\CreateUserUseCase;
use App\Application\UseCases\User\DeleteUserUseCase;
use App\Application\UseCases\User\FindAllUsersUseCase;
use App\Application\UseCases\User\FindUserByIdUseCase;
use App\Application\UseCases\User\UpdateUserUseCase;
use App\Domain\Entities\User;
use App\Domain\Exceptions\EmailAlreadyExistsException;
use App\Domain\Exceptions\UserNotFoundException;
use App\Domain\Exceptions\ValidationException;

class UserController extends AppController
{
    public function __construct(
        private readonly CreateUserUseCase   $createUser,
        private readonly FindAllUsersUseCase $findAllUsers,
        private readonly FindUserByIdUseCase $findUserById,
        private readonly UpdateUserUseCase   $updateUser,
        private readonly DeleteUserUseCase   $deleteUser,
    ) {}

    public function findAll(): void
    {
        echo $this->methodOk('', $this->findAllUsers->execute());
    }

    public function findById(int $id): void
    {
        try {
            $user = $this->findUserById->execute($id);
            echo $this->methodOk('', $this->userToArray($user));
        } catch (UserNotFoundException $e) {
            echo $this->methodNotFound($e->getMessage());
        }
    }

    public function create(): void
    {
        $data = $this->request();

        $required = ['name', 'email', 'password', 'numberPhone', 'rolId'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                echo $this->methodBadRequest("Field '{$field}' is required");
                return;
            }
        }

        $user = new User(
            id:          null,
            name:        $data['name'],
            email:       $data['email'],
            password:    $data['password'],
            numberPhone: $data['numberPhone'],
            roleId:      (int) $data['rolId'],
        );

        try {
            $this->createUser->execute($user);
            echo $this->methodCreated('User created successfully');
        } catch (EmailAlreadyExistsException $e) {
            echo $this->methodConflict($e->getMessage());
        } catch (ValidationException $e) {
            echo $this->methodBadRequest($e->getMessage());
        } catch (\Throwable) {
            echo $this->methodErrorServer('An unexpected error occurred');
        }
    }

    public function update(int $id): void
    {
        $data = $this->request();

        $required = ['name', 'numberPhone', 'rolId'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                echo $this->methodBadRequest("Field '{$field}' is required");
                return;
            }
        }

        $user = new User(
            id:          $id,
            name:        $data['name'],
            email:       '',
            password:    '',
            numberPhone: $data['numberPhone'],
            roleId:      (int) $data['rolId'],
            state:       (int) ($data['state'] ?? 1),
        );

        try {
            $this->updateUser->execute($user);
            echo $this->methodOk('User updated successfully');
        } catch (UserNotFoundException $e) {
            echo $this->methodNotFound($e->getMessage());
        } catch (ValidationException $e) {
            echo $this->methodBadRequest($e->getMessage());
        } catch (\Throwable) {
            echo $this->methodErrorServer('An unexpected error occurred');
        }
    }

    public function delete(int $id): void
    {
        try {
            $this->deleteUser->execute($id);
            echo $this->methodOk('User deleted successfully');
        } catch (UserNotFoundException $e) {
            echo $this->methodNotFound($e->getMessage());
        } catch (\Throwable) {
            echo $this->methodErrorServer('An unexpected error occurred');
        }
    }

    private function userToArray(User $user): array
    {
        return [
            'id'           => $user->id,
            'name'         => $user->name,
            'email'        => $user->email,
            'number_phone' => $user->numberPhone,
            'rol_id'       => $user->roleId,
            'rol'          => $user->roleName,
            'state'        => $user->state,
            'created_at'   => $user->createdAt,
            'updated_at'   => $user->updatedAt,
        ];
    }
}
