<?php


class Model
{

    protected $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function prepareQuery($sql)
    {
        return $this->db->connect()->prepare($sql);
    }

    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    public function validatePassword($password, $passwordData)
    {
        if (password_verify($password, $passwordData)) {
            return true;
        }
        return false;
    }

    public function validate($dato, $value)
    {
        switch ($dato) {
            case 'usuario':
                $sql = "SELECT correo FROM usuarios WHERE correo = :correo";
                $stmt = $this->prepareQuery($sql);
                $stmt->bindParam(':correo', $value, PDO::PARAM_STR);
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

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
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