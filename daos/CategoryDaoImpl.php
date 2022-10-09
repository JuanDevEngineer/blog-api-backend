<?php

namespace Daos;

use PDO;
use PDOException;

use Daos\Repository;
use Models\Category;
use Models\Model;

class CategoryDaoImpl extends Model implements Repository
{

  public function __construct()
  {
    parent::__construct();
  }

  public function create(Category $category)
  {
    try {
      if (!$this->validate('categoria', strtolower($category->name))) {
        $sql = "INSERT INTO categorias (nombre, created_at)
                VALUES(:nombre, CURRENT_TIMESTAMP())";

        $stmt = $this->prepareQuery($sql);

        $stmt->bindParam(':nombre', $category->name, PDO::PARAM_STR);

        if ($stmt->execute()) {
          return array(
            "success" => true,
            "msg" => 'Category registered'
          );
        } else {
          return array(
            "success" => false,
            "msg" => 'Error registering the category'
          );
        }
      } else {
        return array(
          "success" => false,
          "msg" => 'Category already exists'
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

      $sql = "SELECT id, nombre FROM categorias";

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
        'success' => false,
        'msg' => $e->getMessage()
      );
    }
  }

  public function findById($id)
  {
    try {
      $sql = "SELECT id, nombre FROM categorias
                    WHERE id = :id";
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
        'success' => false,
        'msg' => $e->getMessage()
      );
    }
  }

  public function update(Category $category)
  {
    try {
      $sql = "UPDATE categorias
                    SET nombre = ?, updated_at = CURRENT_TIMESTAMP()
                    WHERE id = ?";

      $stmt = $this->prepareQuery($sql);

      $stmt->bindParam(1, $category->name, PDO::PARAM_STR);
      $stmt->bindParam(2, $category->id, PDO::PARAM_INT);

      if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
          return array(
            "success" => true,
            "msg" => 'Category updated'
          );
        } else {
          return array(
            "success" => false,
            "msg" => 'Error updating category'
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
      $sql = "DELETE FROM categorias
                    WHERE id = :id";

      $stmt = $this->prepareQuery($sql);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);

      if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
          return array(
            "success" => true,
            "msg" => 'Category deleted'
          );
        } else {
          return array(
            "success" => false,
            "msg" => 'Error when deleting the category'
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
