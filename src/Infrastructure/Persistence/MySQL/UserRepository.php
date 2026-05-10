<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\MySQL;

use PDO;
use App\Domain\Entities\User;
use App\Domain\Ports\Output\UserRepositoryPort;
use App\Infrastructure\Database\DatabaseConnection;

class UserRepository implements UserRepositoryPort
{
    private PDO $pdo;

    public function __construct(DatabaseConnection $db)
    {
        $this->pdo = $db->getConnection();
    }

    public function findAll(): array
    {
        $sql = "SELECT
                    u.id,
                    u.name,
                    u.email,
                    u.number_phone,
                    u.rol_id,
                    r.name AS rol,
                    u.state,
                    u.created_at,
                    CASE WHEN u.updated_at THEN u.updated_at ELSE 'N/A' END AS updated_at
                FROM users u
                INNER JOIN roles r ON r.id = u.rol_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function findById(int $id): ?User
    {
        $sql = "SELECT
                    u.id,
                    u.name,
                    u.email,
                    u.number_phone,
                    u.rol_id,
                    r.name AS rol,
                    u.state,
                    u.created_at,
                    CASE WHEN u.updated_at THEN u.updated_at ELSE 'N/A' END AS updated_at
                FROM users u
                INNER JOIN roles r ON r.id = u.rol_id
                WHERE u.id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new User(
            id:          (int) $row['id'],
            name:        $row['name'],
            email:       $row['email'],
            password:    '',
            numberPhone: $row['number_phone'],
            roleId:      (int) $row['rol_id'],
            state:       (int) $row['state'],
            roleName:    $row['rol'],
            createdAt:   $row['created_at'] ?? null,
            updatedAt:   $row['updated_at'] ?? null,
        );
    }

    public function findByEmail(string $email): ?User
    {
        $sql = "SELECT
                    u.id,
                    u.name,
                    u.email,
                    u.password,
                    u.number_phone,
                    u.rol_id,
                    r.name AS rol,
                    u.state
                FROM users u
                INNER JOIN roles r ON r.id = u.rol_id
                WHERE u.email = :email";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        // BUG FIX: el UserDao original retornaba 'success' => false cuando el usuario SÍ existía
        return new User(
            id:          (int) $row['id'],
            name:        $row['name'],
            email:       $row['email'],
            password:    $row['password'],
            numberPhone: $row['number_phone'],
            roleId:      (int) $row['rol_id'],
            state:       (int) $row['state'],
            roleName:    $row['rol'],
        );
    }

    public function emailExists(string $email): bool
    {
        $sql  = "SELECT id FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function create(User $user): bool
    {
        $sql = "INSERT INTO users
                    (name, email, password, number_phone, rol_id, created_at)
                VALUES
                    (:name, :email, :password, :number_phone, :rol_id, CURRENT_TIMESTAMP())";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':name',         $user->name,        PDO::PARAM_STR);
        $stmt->bindParam(':email',        $user->email,       PDO::PARAM_STR);
        $stmt->bindParam(':password',     $user->password,    PDO::PARAM_STR);
        $stmt->bindParam(':number_phone', $user->numberPhone, PDO::PARAM_STR);
        $stmt->bindParam(':rol_id',       $user->roleId,      PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function update(User $user): bool
    {
        $sql = "UPDATE users
                SET
                    name         = :name,
                    number_phone = :number_phone,
                    rol_id       = :rol_id,
                    state        = :state,
                    updated_at   = CURRENT_TIMESTAMP()
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':name',         $user->name,        PDO::PARAM_STR);
        $stmt->bindParam(':number_phone', $user->numberPhone, PDO::PARAM_STR);
        $stmt->bindParam(':rol_id',       $user->roleId,      PDO::PARAM_INT);
        $stmt->bindParam(':state',        $user->state,       PDO::PARAM_INT);
        $stmt->bindParam(':id',           $user->id,          PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $sql  = "DELETE FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}
