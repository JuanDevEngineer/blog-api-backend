<?php

require_once 'models/model.php';

class UsuariosModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function create($data)
    {
        try {

            if (!$this->validate('usuario', $data['correo'])) {

                $password_Hash = $this->hashPassword($data['password']);

                $sql = "INSERT INTO usuarios (nombre, correo, contrasena, numero_movil, tipo_usuario, fecha_creacion)
                        VALUES(:nombre, :correo, :contrasena, :numero_movil, :tipo_usuario, :fecha_creacion)";

                $stmt = $this->prepareQuery($sql);

                $stmt->bindParam(':nombre', $data['nombre'], PDO::PARAM_STR);
                $stmt->bindParam(':correo', $data['correo'], PDO::PARAM_STR);
                $stmt->bindParam(':contrasena', $password_Hash, PDO::PARAM_STR);
                $stmt->bindParam(':numero_movil', $data['numero_movil'], PDO::PARAM_STR);
                $stmt->bindParam(':tipo_usuario', $data['tipo_usuario'], PDO::PARAM_INT);
                $stmt->bindParam(':fecha_creacion', $data['fecha_creacion']);

                if ($stmt->execute()) {
                    return array(
                        "success" => true,
                        "msg" => 'usuario registrado'
                    );
                } else {
                    return array(
                        "success" => false,
                        "msg" => 'error al registrar el usuario'
                    );
                }
            } else {
                return array(
                    "success" => false,
                    "msg" => 'el correo ya existe'
                );
            }
        } catch (PDOException $e) {
            return array(
                'success' => false,
                'msg' => $e->getMessage()
            );
        }
    }

    public function getAll()
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

    public function getOne($id)
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

    public function update($data)
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

            $stmt->bindParam(':nombre', $data['nombre'], PDO::PARAM_STR);
            $stmt->bindParam(':numero_movil', $data['numero_movil'], PDO::PARAM_STR);
            $stmt->bindParam(':tipo_usuario', $data['tipo_usuario'], PDO::PARAM_INT);
            $stmt->bindParam(':fecha_actualizacion', $data['fechaActualizacion']);
            $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);

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
