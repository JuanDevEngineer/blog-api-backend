<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\MySQL;

use PDO;
use App\Domain\Entities\Role;
use App\Domain\Ports\Output\RoleRepositoryPort;
use App\Infrastructure\Database\DatabaseConnection;

class RoleRepository implements RoleRepositoryPort
{
    private PDO $pdo;

    public function __construct(DatabaseConnection $db)
    {
        $this->pdo = $db->getConnection();
    }

    public function findAll(): array
    {
        $sql = "SELECT
                    id,
                    name,
                    state,
                    created_at,
                    CASE WHEN updated_at THEN updated_at ELSE 'N/A' END AS updated_at
                FROM roles";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function findById(int $id): ?Role
    {
        $sql = "SELECT
                    id,
                    name,
                    state,
                    created_at,
                    CASE WHEN updated_at THEN updated_at ELSE 'N/A' END AS updated_at
                FROM roles
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Role(
            id:        (int) $row['id'],
            name:      $row['name'],
            state:     (int) $row['state'],
            createdAt: $row['created_at'] ?? null,
            updatedAt: $row['updated_at'] ?? null,
        );
    }

    public function create(Role $role): bool
    {
        $sql  = "INSERT INTO roles (name, created_at) VALUES (:name, CURRENT_TIMESTAMP())";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':name', $role->name, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function update(Role $role): bool
    {
        $sql = "UPDATE roles
                SET
                    name       = :name,
                    state      = :state,
                    updated_at = CURRENT_TIMESTAMP()
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':name',  $role->name,  PDO::PARAM_STR);
        $stmt->bindParam(':state', $role->state, PDO::PARAM_INT);
        $stmt->bindParam(':id',    $role->id,    PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $sql  = "DELETE FROM roles WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}
