<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\MySQL;

use PDO;
use App\Domain\Entities\Category;
use App\Domain\Ports\Output\CategoryRepositoryPort;
use App\Infrastructure\Database\DatabaseConnection;

class CategoryRepository implements CategoryRepositoryPort
{
    private PDO $pdo;

    public function __construct(DatabaseConnection $db)
    {
        $this->pdo = $db->getConnection();
    }

    public function findAll(): array
    {
        $sql  = "SELECT id, name, created_at FROM categories";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function findById(int $id): ?Category
    {
        $sql  = "SELECT id, name, state, created_at, updated_at FROM categories WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Category(
            id:        (int) $row['id'],
            name:      $row['name'],
            state:     (int) ($row['state'] ?? 1),
            createdAt: $row['created_at'] ?? null,
            updatedAt: $row['updated_at'] ?? null,
        );
    }

    public function nameExists(string $name): bool
    {
        $sql  = "SELECT id FROM categories WHERE LOWER(name) = LOWER(:name)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function create(Category $category): bool
    {
        $sql  = "INSERT INTO categories (name, created_at) VALUES (:name, CURRENT_TIMESTAMP())";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':name', $category->name, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function update(Category $category): bool
    {
        $sql  = "UPDATE categories SET name = :name, updated_at = CURRENT_TIMESTAMP() WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':name', $category->name, PDO::PARAM_STR);
        $stmt->bindParam(':id',   $category->id,   PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $sql  = "DELETE FROM categories WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}
