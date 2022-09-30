<?php

namespace Models;

use PDO;
use Config\Database;

class Model
{
  protected $db;

  public function __construct()
  {
    $this->db = Database::getInstance();
  }

  public function prepareQuery($sql)
  {
    return $this->db->getPool()->prepare($sql);
  }

  public function hashPassword($password)
  {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
  }

  public function validatePassword($password, $passwordData)
  {
    return password_verify($password, $passwordData);
  }

  public function validate($dato, $value)
  {
    switch ($dato) {
      case 'usuario':
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->prepareQuery($sql);
        $stmt->bindParam(':email', $value, PDO::PARAM_STR);
        break;

      case 'categoria':
        $sql = "SELECT nombre FROM categorias WHERE nombre = :nombre";
        $stmt = $this->prepareQuery($sql);
        $stmt->bindParam(':nombre', $value, PDO::PARAM_STR);
        break;

      default:
        return false;
        break;
    }

    if ($stmt->execute()) {
      return $stmt->rowCount() > 0;
    } else {
      return false;
    }
  }

  // public function validateEmail($email)
  // {
  //     $sql = "SELECT correo FROM usuarios WHERE correo = :correo";
  //     $stmt = $this->prepareQuery($sql);
  //     $stmt->bindParam(':correo', $email, PDO::PARAM_STR);

  //     if ($stmt->execute()) {

  //         if ($stmt->rowCount() > 0) {
  //             return true;
  //         } else {
  //             return false;
  //         }
  //     } else {
  //         return false;
  //     }
  // }

  // public function validateCategoria($categoria)
  // {
  //     $sql = "SELECT nombre FROM categorias WHERE nombre = :nombre";
  //     $stmt = $this->prepareQuery($sql);
  //     $stmt->bindParam(':nombre', $categoria, PDO::PARAM_STR);

  //     if ($stmt->execute()) {

  //         if ($stmt->rowCount() > 0) {
  //             return true;
  //         } else {
  //             return false;
  //         }
  //     } else {
  //         return false;
  //     }
  // }



}
