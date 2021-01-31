<?php

require_once 'models/model.php';

class CategoriasModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function create($categoria)
    {
        try {
            if (!$this->validate('categoria', $categoria)) {
                $sql = "INSERT INTO categorias (nombre)
                VALUES(:nombre)";

                $stmt = $this->prepareQuery($sql);

                $stmt->bindParam(':nombre', $categoria, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    return array(
                        "success" => true,
                        "msg" => 'categoria registrada'
                    );
                } else {
                    return array(
                        "success" => false,
                        "msg" => 'error al registrar la categoria'
                    );
                }
            } else {
                return array(
                    "success" => false,
                    "msg" => 'la categoria ya existe'
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

            $sql = "SELECT * FROM categorias";

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

            $sql = "SELECT *
                    FROM categorias
                    WHERE id_categoria = :id";

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

            $sql = "UPDATE categorias
                    SET nombre = :nombre,
                    WHERE id_categoria = :id";

            $stmt = $this->prepareQuery($sql);

            $stmt->bindParam(':nombre', $data['nombre'], PDO::PARAM_STR);
            $stmt->bindParam(':id', $data['id_categoria'], PDO::PARAM_INT);

            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    return array(
                        "success" => true,
                        "msg" => 'categoria actualizado'
                    );
                } else {
                    return array(
                        "success" => false,
                        "msg" => 'error al actualizar la categoria'
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
                    FROM categorias
                    WHERE id_categoria = :id";

            $stmt = $this->prepareQuery($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    return array(
                        "success" => true,
                        "msg" => 'categoria eliminada'
                    );
                } else {
                    return array(
                        "success" => false,
                        "msg" => 'error al eliminar la categoria'
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
