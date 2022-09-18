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

      $sql = "INSERT INTO blog (id_categoria, titulo, slug, texto_corto, texto_largo, image_path, created_at)
                    VALUES(:id_categoria, :titulo, :slug, :texto_corto, :texto_largo, :nombre_imagen, :fecha_creacion)";

      $stmt = $this->prepareQuery($sql);

      $stmt->bindParam(':id_categoria', $blog->id_categoria, PDO::PARAM_INT);
      $stmt->bindParam(':titulo', $blog->titulo, PDO::PARAM_STR);
      $stmt->bindParam(':slug', $blog->slug, PDO::PARAM_STR);
      $stmt->bindParam(':texto_corto', $blog->texto_corto, PDO::PARAM_STR);
      $stmt->bindParam(':texto_largo', $blog->texto_largo, PDO::PARAM_STR);
      $stmt->bindParam(':nombre_imagen', $blog->url_imagen, PDO::PARAM_STR);
      $stmt->bindParam(':fecha_creacion', $blog->fecha_creacion);

      if ($stmt->execute()) {
        return array(
          "success" => true,
          "msg" => 'blog registrado'
        );
      } else {
        return array(
          "success" => false,
          "msg" => 'error al registrar el blog'
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
                    b.titulo,
                    b.texto_corto,
                    b.id_categoria,
                    c.nombre as 'categoria',
                    b.slug,
                    b.image_path
                    FROM blog b
                    INNER JOIN categorias c ON c.id = b.id_categoria";

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
                    b.titulo,
                    b.id_categoria,
                    c.nombre as 'categoria',
                    b.texto_corto,
                    b.texto_largo,
                    b.image_path
                    FROM blog b
                    INNER JOIN categorias c ON c.id = b.id_categoria
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
      $sql = "UPDATE blog b
                    SET
                    b.titulo = :titulo,
                    b.slug = :slug,
                    b.texto_corto = :texto_corto,
                    b.texto_largo = :texto_largo,
                    b.updated_at = :fecha_actualizacion
                    WHERE b.id = :id";

      $stmt = $this->prepareQuery($sql);

      $stmt->bindParam(':titulo', $blog->titulo, PDO::PARAM_STR);
      $stmt->bindParam(':slug', $blog->slug, PDO::PARAM_STR);
      $stmt->bindParam(':texto_corto', $blog->texto_corto, PDO::PARAM_STR);
      $stmt->bindParam(':texto_largo', $blog->texto_largo, PDO::PARAM_STR);
      $stmt->bindParam(':fecha_actualizacion', $blog->fecha_actualizacion);
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
      $sql = "DELETE FROM blog WHERE id = :id";

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
