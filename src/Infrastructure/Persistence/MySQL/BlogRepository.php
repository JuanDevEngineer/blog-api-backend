<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\MySQL;

use PDO;
use App\Domain\Entities\Blog;
use App\Domain\Ports\Output\BlogRepositoryPort;
use App\Infrastructure\Database\DatabaseConnection;

class BlogRepository implements BlogRepositoryPort
{
    private PDO $pdo;

    public function __construct(DatabaseConnection $db)
    {
        $this->pdo = $db->getConnection();
    }

    public function findAll(): array
    {
        $sql = "SELECT
                    b.id,
                    b.title,
                    b.slug,
                    b.text_short,
                    b.text_large,
                    c.name AS category,
                    b.path_image,
                    b.created_at
                FROM blogs b
                INNER JOIN categories c ON c.id = b.category_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function findById(int $id): ?Blog
    {
        $sql = "SELECT
                    b.id,
                    b.category_id,
                    b.title,
                    b.slug,
                    b.text_short,
                    b.text_large,
                    b.path_image,
                    b.created_at,
                    b.updated_at
                FROM blogs b
                WHERE b.id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Blog(
            id:         (int) $row['id'],
            categoryId: (int) $row['category_id'],
            title:      $row['title'],
            slug:       $row['slug'],
            textShort:  $row['text_short'],
            textLarge:  $row['text_large'],
            pathImage:  $row['path_image'] ?? '',
            createdAt:  $row['created_at'] ?? null,
            updatedAt:  $row['updated_at'] ?? null,
        );
    }

    public function create(Blog $blog): bool
    {
        $sql = "INSERT INTO blogs
                    (category_id, title, slug, text_short, text_large, path_image, created_at)
                VALUES
                    (:category_id, :title, :slug, :text_short, :text_large, :path_image, CURRENT_TIMESTAMP())";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':category_id', $blog->categoryId, PDO::PARAM_INT);
        $stmt->bindParam(':title',       $blog->title,      PDO::PARAM_STR);
        $stmt->bindParam(':slug',        $blog->slug,       PDO::PARAM_STR);
        $stmt->bindParam(':text_short',  $blog->textShort,  PDO::PARAM_STR);
        $stmt->bindParam(':text_large',  $blog->textLarge,  PDO::PARAM_STR);
        $stmt->bindParam(':path_image',  $blog->pathImage,  PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function update(Blog $blog): bool
    {
        // BUG FIX: faltaba coma después de category_id y los nombres de propiedades eran incorrectos
        $sql = "UPDATE blogs
                SET
                    category_id = :category_id,
                    title       = :title,
                    slug        = :slug,
                    text_short  = :text_short,
                    text_large  = :text_large,
                    updated_at  = CURRENT_TIMESTAMP()
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':category_id', $blog->categoryId, PDO::PARAM_INT);
        $stmt->bindParam(':title',       $blog->title,      PDO::PARAM_STR);
        $stmt->bindParam(':slug',        $blog->slug,       PDO::PARAM_STR);
        $stmt->bindParam(':text_short',  $blog->textShort,  PDO::PARAM_STR);
        $stmt->bindParam(':text_large',  $blog->textLarge,  PDO::PARAM_STR);
        $stmt->bindParam(':id',          $blog->id,         PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $sql  = "DELETE FROM blogs WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}
