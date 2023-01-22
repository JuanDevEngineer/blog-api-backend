<?php

namespace Daos;

use PDO;
use PDOException;

use Models\Model;
use Models\Role;


class RoleDao extends Model implements IRole
{
  public function __construct()
  {
    parent::__construct();
  }

  public function create(Role $rol): array
  {
    try {
      $sql = "INSERT INTO roles(name, created_at) VALUES(:name, CURRENT_TIMESTAMP())";
      $stmt = $this->prepareQuery($sql);

      $rol->name = strtoupper($rol->name);

      $stmt->bindParam(':name', $rol->name, PDO::PARAM_STR);
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
      $sql = "SELECT id, name, state, created_at, CASE
      WHEN updated_at THEN updated_at
      ELSE 'N/A'
      END as 'updated_at' FROM roles";
      $stmt = $this->prepareQuery($sql);
      if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
	        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
      $sql = "SELECT id, name, state, created_at, CASE
      WHEN updated_at THEN updated_at
      ELSE 'N/A'
      END as 'updated_at' FROM roles WHERE id = :id";
      $stmt = $this->prepareQuery($sql);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);

      if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
	        return $stmt->fetch(PDO::FETCH_ASSOC);
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
      $sql = "UPDATE roles
                    SET name = ?, updated_at = CURRENT_TIMESTAMP()
                    WHERE id = ? AND state = ?";

      $stmt = $this->prepareQuery($sql);

      $stmt->bindParam(1, $rol->name, PDO::PARAM_STR);
      $stmt->bindParam(2, $rol->id, PDO::PARAM_INT);
      $stmt->bindParam(3, $rol->state, PDO::PARAM_INT);

      if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
          return array(
            "success" => true,
            "msg" => 'Rol updated'
          );
        } else {
          return array(
            "success" => false,
            "msg" => 'Error updating Rol'
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

  public function delete($id)
  {
    try {
      $sql = "DELETE FROM roles WHERE id = :id AND state = 0";
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
