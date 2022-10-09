<?php

namespace Daos;

use PDO;
use PDOException;

use Daos\Repository;
use Models\Model;
use Models\Role;


class RoleDaoImpl extends Model implements Repository
{
  public function __construct()
  {
    parent::__construct();
  }

  public function create(Role $rol)
  {
    try {
      $sql = "INSERT INTO tipos_usuarios(nombre, created_at) VALUES(:nombre, CURRENT_TIMESTAMP())";
      $stmt = $this->prepareQuery($sql);

      $stmt->bindParam(':nombre', strtoupper($rol->name), PDO::PARAM_STR);
      if ($stmt->execute()) {
        return array(
          "success" => true,
          "msg" => 'Role registered'
        );
      } else {
        return array(
          "success" => false,
          "msg" => 'Error registering the role'
        );
      }
    } catch (PDOException $e) {
      header('HTTP/1.1 500 Internal Server Error');
      return array(
        'success' => false,
        'msg' => $e->getMessage()
      );
    }
  }

  public function findAll()
  {
    try {
      $sql = "SELECT * FROM tipos_usuarios";
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
      header('HTTP/1.1 500 Internal Server Error');
      return array(
        'success' => false,
        'msg' => $e->getMessage()
      );
    }
  }

  public function findById($id)
  {
    try {
      $sql = "SELECT * FROM tipos_usuarios WHERE id = :id";
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
      header('HTTP/1.1 500 Internal Server Error');
      return array(
        'success' => false,
        'msg' => $e->getMessage()
      );
    }
  }

  public function update(Role $rol)
  {
    try {
    } catch (PDOException $e) {
      header('HTTP/1.1 500 Internal Server Error');
      return array(
        'success' => false,
        'msg' => $e->getMessage()
      );
    }
  }

  public function delete($id)
  {
    try {
      $sql = "DELETE FROM tipos_usuarios WHERE id = :id";
      $stmt = $this->prepareQuery($sql);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);

      if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
          return array(
            "success" => true,
            "msg" => 'Role deleted'
          );
        } else {
          return array(
            "success" => false,
            "msg" => 'Error when deleting the Role'
          );
        }
      }
    } catch (PDOException $e) {
      header('HTTP/1.1 500 Internal Server Error');
      return array(
        'success' => false,
        'msg' => $e->getMessage()
      );
    }
  }
}
