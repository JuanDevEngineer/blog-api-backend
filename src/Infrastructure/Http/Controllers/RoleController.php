<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Application\UseCases\Role\CreateRoleUseCase;
use App\Application\UseCases\Role\DeleteRoleUseCase;
use App\Application\UseCases\Role\FindAllRolesUseCase;
use App\Application\UseCases\Role\FindRoleByIdUseCase;
use App\Application\UseCases\Role\UpdateRoleUseCase;
use App\Domain\Entities\Role;
use App\Domain\Exceptions\RoleNotFoundException;
use App\Domain\Exceptions\ValidationException;

class RoleController extends AppController
{
    public function __construct(
        private readonly CreateRoleUseCase   $createRole,
        private readonly FindAllRolesUseCase $findAllRoles,
        private readonly FindRoleByIdUseCase $findRoleById,
        private readonly UpdateRoleUseCase   $updateRole,
        private readonly DeleteRoleUseCase   $deleteRole,
    ) {}

    public function findAll(): void
    {
        echo $this->methodOk('', $this->findAllRoles->execute());
    }

    public function findById(int $id): void
    {
        try {
            $role = $this->findRoleById->execute($id);
            echo $this->methodOk('', $this->roleToArray($role));
        } catch (RoleNotFoundException $e) {
            echo $this->methodNotFound($e->getMessage());
        }
    }

    public function create(): void
    {
        $data = $this->request();

        if (empty($data['name'])) {
            echo $this->methodBadRequest('Role name is required');
            return;
        }

        try {
            $this->createRole->execute(new Role(id: null, name: $data['name']));
            echo $this->methodCreated('Role created successfully');
        } catch (ValidationException $e) {
            echo $this->methodBadRequest($e->getMessage());
        } catch (\Throwable) {
            echo $this->methodErrorServer('An unexpected error occurred');
        }
    }

    public function update(int $id): void
    {
        $data = $this->request();

        if (empty($data['name'])) {
            echo $this->methodBadRequest('Role name is required');
            return;
        }

        $role = new Role(
            id:    $id,
            name:  $data['name'],
            state: (int) ($data['state'] ?? 1),
        );

        try {
            $this->updateRole->execute($role);
            echo $this->methodOk('Role updated successfully');
        } catch (RoleNotFoundException $e) {
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
            $this->deleteRole->execute($id);
            echo $this->methodOk('Role deleted successfully');
        } catch (RoleNotFoundException $e) {
            echo $this->methodNotFound($e->getMessage());
        } catch (\Throwable) {
            echo $this->methodErrorServer('An unexpected error occurred');
        }
    }

    private function roleToArray(Role $role): array
    {
        return [
            'id'         => $role->id,
            'name'       => $role->name,
            'state'      => $role->state,
            'created_at' => $role->createdAt,
            'updated_at' => $role->updatedAt,
        ];
    }
}
