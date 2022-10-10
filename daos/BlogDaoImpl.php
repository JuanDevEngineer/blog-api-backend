<?php

namespace Daos;

use PDO;
use PDOException;

use Daos\Repository;
use Models\Blog;
use Models\Model;

class BlogDaoImpl extends Model implements Repository
{
  public function __construct()
  {
    parent::__construct();
  }

  public function create(Blog $blog)
  {
    try {
      $sql = "INSERT INTO blogs (category_id, title, slug, text_short, text_large, path_image, created_at)
                    VALUES(:category_id, :title, :slug, :text_short, :text_large, :path_image, CURRENT_TIMESTAMP())";

      $stmt = $this->prepareQuery($sql);

      $stmt->bindParam(':category_id', $blog->category_id, PDO::PARAM_INT);
      $stmt->bindParam(':title', $blog->title, PDO::PARAM_STR);
      $stmt->bindParam(':slug', $blog->slug, PDO::PARAM_STR);
      $stmt->bindParam(':text_short', $blog->text_short, PDO::PARAM_STR);
      $stmt->bindParam(':text_large', $blog->text_large, PDO::PARAM_STR);
      $stmt->bindParam(':path_image', $blog->path_image, PDO::PARAM_STR);

      if ($stmt->execute()) {
        return array(
          "success" => true,
          "msg" => 'Blog registered'
        );
      } else {
        return array(
          "success" => false,
          "msg" => 'Error al registrar el blog'
        );
      }
    } catch (PDOException $e) {
      return array(
        'success' => false,
        'msg' => $e->getMessage()
      );
    }
  }

  public function findAll()
  {
    try {
      $sql = "SELECT
                    b.id,
                    b.title,
                    b.slug,
                    b.text_short,
                    b.text_large,
                    c.name as 'category',
                    b.path_image,
                    b.created_at
                    FROM blogs b
                    INNER JOIN categories c ON c.id = b.category_id";

      $stmt = $this->prepareQuery($sql);

      if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
          $data = $stmt->fetchAll();
          return $data;
        } else {
          return array();
        }
      }
    } catch (PDOException $e) {
      return array(
        'msg' => $e->getMessage()
      );
    }
  }

  public function findById($id)
  {
    try {
      $sql = "SELECT
                    b.id,
                    b.title,
                    b.category_id,
                    c.name as 'category',
                    b.text_short,
                    b.text_large,
                    b.path_image
                    FROM blogs b
                    INNER JOIN categories c ON c.id = b.category_id
                    WHERE b.id = :id";

      $stmt = $this->prepareQuery($sql);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);

      if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
          $data = $stmt->fetch();
          return $data;
        } else {
          return array();
        }
      }
    } catch (PDOException $e) {
      return array(
        'msg' => $e->getMessage()
      );
    }
  }

  public function update(Blog $blog)
  {
    try {
      $sql = "UPDATE blogs b
                    SET
                    b.category_id = :category_id
                    b.title = :title,
                    b.slug = :slug,
                    b.text_short = :text_short,
                    b.text_large = :text_large,
                    b.updated_at = CURRENT_TIMESTAMP()
                    WHERE b.id = :id";

      $stmt = $this->prepareQuery($sql);

      $stmt->bindParam(':title', $blog->titulo, PDO::PARAM_STR);
      $stmt->bindParam(':slug', $blog->slug, PDO::PARAM_STR);
      $stmt->bindParam(':text_short', $blog->text_short, PDO::PARAM_STR);
      $stmt->bindParam(':text_large', $blog->txt_large, PDO::PARAM_STR);
      $stmt->bindParam(':category_id', $blog->category_id, PDO::PARAM_INT);
      $stmt->bindParam(':id', $blog->id, PDO::PARAM_INT);

      if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
          return array(
            "success" => true,
            "msg" => 'blog actualizado'
          );
        } else {
          return array(
            "success" => false,
            "msg" => 'error al actualizar el blog'
          );
        }
      }
    } catch (PDOException $e) {
      return array(
        'success' => false,
        'msg' => $e->getMessage()
      );
    }
  }

  public function delete($id)
  {
    try {
      $sql = "DELETE FROM blogs WHERE id = :id AND state = 0";

      $stmt = $this->prepareQuery($sql);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);

      if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
          return array(
            "success" => true,
            "msg" => 'blog eliminado'
          );
        } else {
          return array(
            "success" => false,
            "msg" => 'error al eliminar el blog'
          );
        }
      }
    } catch (PDOException $e) {
      return array(
        'success' => false,
        'msg' => $e->getMessage()
      );
    }
  }
}
