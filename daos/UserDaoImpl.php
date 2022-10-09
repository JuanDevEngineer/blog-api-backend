<?php

namespace Daos;

use PDO;
use PDOException;

use Daos\Repository;
use Models\Model;
use Models\User;


class UserDaoImpl extends Model implements Repository
{
  public function __construct()
  {
    parent::__construct();
  }

  public function register(User $user)
  {
  }

  public function create(User $user)
  {
    try {
      if (!$this->validate('usuario', $user->email)) {

        $password_Hash = $this->hashPassword($user->password);

        $sql = "INSERT INTO users (name, email, password, phone, tipo_id, created_at)
                        VALUES(:nombre, :correo, :contrasena, :numero_movil, :tipo_usuario, CURRENT_TIMESTAMP())";

        $stmt = $this->prepareQuery($sql);

        $stmt->bindParam(':nombre', $user->name, PDO::PARAM_STR);
        $stmt->bindParam(':correo', $user->email, PDO::PARAM_STR);
        $stmt->bindParam(':contrasena', $password_Hash, PDO::PARAM_STR);
        $stmt->bindParam(':numero_movil', $user->numberPhone, PDO::PARAM_STR);
        $stmt->bindParam(':tipo_usuario', $user->typeUser, PDO::PARAM_INT);

        if ($stmt->execute()) {
          return array(
            "success" => true,
            "msg" => 'User created successfull'
          );
        } else {
          return array(
            "success" => false,
            "msg" => 'Error to register user'
          );
        }
      } else {
        return array(
          "success" => false,
          "msg" => 'Email already exists'
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
      $sql = "SELECT
                    u.id,
                    u.name,
                    u.email,
                    u.phone,
                    u.tipo_id,
                    tu.nombre as 'tipo',
                    u.created_at,
                    CASE
                        WHEN u.updated_at THEN u.updated_at
                        ELSE 'N/A'
                    END as 'fecha_actualiza'
                    FROM users u
                    INNER JOIN tipos_usuarios tu ON tu.id = u.tipo_id";

      $stmt = $this->prepareQuery($sql);

      if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
          $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

      $sql = "SELECT
                    u.id,
                    u.name,
                    u.email,
                    u.phone,
                    u.tipo_id,
                    tu.nombre as 'tipo',
                    u.updated_at,
                    CASE
                        WHEN u.updated_at THEN u.updated_at
                        ELSE 'N/A'
                    END as 'fecha_ac'
                    FROM users u
                    INNER JOIN tipos_usuarios tu ON tu.id = u.tipo_id
                    WHERE u.id = :id";

      $stmt = $this->prepareQuery($sql);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);

      if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
          $data = $stmt->fetch(PDO::FETCH_ASSOC);
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

  public function existUser(User $user)
  {
    try {
      return $this->validate('usuario', $user->email);
    } catch (PDOException $e) {
      header('HTTP/1.1 500 Internal Server Error');
      return array(
        'success' => false,
        'msg' => $e->getMessage()
      );
    }
  }

  public function findByEmail(User $user)
  {
    try {
      $sql = "SELECT u.id, u.name, u.email, u.password, u.tipo_id, t.nombre as rol FROM users u
              INNER JOIN tipos_usuarios t ON t.id = u.tipo_id
              WHERE u.email = :email";

      $stmt = $this->prepareQuery($sql);
      $stmt->bindParam(':email', $user->email, PDO::PARAM_STR);

      if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
          $data = $stmt->fetch();
          return array('success' => false, 'data' => $data);
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

  public function update(User $user)
  {
    try {
      $sql = "UPDATE users u
                    SET
                    u.name = :nombre,
                    u.phone = :numero_movil,
                    u.tipo_id = :tipo_usuario,
                    u.updated_at = CURRENT_TIMESTAMP()
                    WHERE u.id = :id";

      $stmt = $this->prepareQuery($sql);

      $stmt->bindParam(':nombre', $user->name, PDO::PARAM_STR);
      $stmt->bindParam(':numero_movil', $user->numberPhone, PDO::PARAM_STR);
      $stmt->bindParam(':tipo_usuario', $user->typeUser, PDO::PARAM_INT);
      $stmt->bindParam(':id', $user->id, PDO::PARAM_INT);

      if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
          return array(
            "success" => true,
            "msg" => 'User updated successfull'
          );
        } else {
          return array(
            "success" => false,
            "msg" => 'Error updating user'
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
      $sql = "DELETE FROM users WHERE id = :id";
      $stmt = $this->prepareQuery($sql);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);

      if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
          return array(
            "success" => true,
            "msg" => 'User deleted successfull'
          );
        } else {
          return array(
            "success" => false,
            "msg" => 'Error deleting user'
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
