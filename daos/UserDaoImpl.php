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
                    u.nombre,
                    u.correo,
                    u.numero_movil,
                    u.tipo_usuario,
                    tu.nombre as 'tipo',
                    u.fecha_creacion,
                    CASE
                        WHEN u.fecha_actualizacion THEN u.fecha_actualizacion
                        ELSE 'N/A'
                    END as 'fecha_actualiza'
                    FROM usuarios u
                    INNER JOIN tipo_usuarios tu ON tu.id_tipo = u.tipo_usuario";

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
      return array(
        'msg' => $e->getMessage()
      );
    }
  }

  public function findById($id)
  {
    try {

      $sql = "SELECT
                    u.id,
                    u.nombre,
                    u.correo,
                    u.numero_movil,
                    u.tipo_usuario,
                    tu.nombre as 'tipo',
                    u.fecha_creacion,
                    CASE
                        WHEN u.fecha_actualizacion THEN u.fecha_actualizacion
                        ELSE 'N/A'
                    END as 'fecha_ac'
                    FROM usuarios u
                    INNER JOIN tipo_usuarios tu ON tu.id_tipo = u.tipo_usuario
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
      return array(
        'msg' => $e->getMessage()
      );
    }
  }

  public function existUser(User $user)
  {
    return $this->validate('usuario', $user->email);
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
      return array(
        'success' => false,
        'msg' => $e->getMessage()
      );
    }
  }

  public function update(User $user)
  {
    try {
      $sql = "UPDATE usuarios u
                    SET
                    u.nombre = :nombre,
                    u.numero_movil = :numero_movil,
                    u.tipo_usuario = :tipo_usuario,
                    u.fecha_actualizacion = :fecha_actualizacion
                    WHERE u.id = :id";

      $stmt = $this->prepareQuery($sql);

      $stmt->bindParam(':nombre', $user->nombre, PDO::PARAM_STR);
      $stmt->bindParam(':numero_movil', $user->numero_movil, PDO::PARAM_STR);
      $stmt->bindParam(':tipo_usuario', $user->tipo_usuario, PDO::PARAM_INT);
      $stmt->bindParam(':fecha_actualizacion', $user->fechaActualizacion);
      $stmt->bindParam(':id', $user->id, PDO::PARAM_INT);

      if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
          return array(
            "success" => true,
            "msg" => 'usuario actualizado'
          );
        } else {
          return array(
            "success" => false,
            "msg" => 'error al actualizar el usuario'
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

      $sql = "DELETE 
                    FROM usuarios
                    WHERE id = :id";

      $stmt = $this->prepareQuery($sql);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);

      if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
          return array(
            "success" => true,
            "msg" => 'usuario eliminado'
          );
        } else {
          return array(
            "success" => false,
            "msg" => 'error al eliminar el usuario'
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
