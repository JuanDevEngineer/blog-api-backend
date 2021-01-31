<?php

require_once 'models/model.php';

class BlogsModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function create($data)
    {
        try {

            $sql = "INSERT
                    INTO blogs (id_categoria, titulo, slug, texto_corto, texto_largo, nombre_imagen, fecha_creacion)
                    VALUES(:id_categoria, :titulo, :slug, :texto_corto, :texto_largo, :nombre_imagen, :fecha_creacion)";

            $stmt = $this->prepareQuery($sql);

            $stmt->bindParam(':id_categoria', $data['id_categoria'], PDO::PARAM_INT);
            $stmt->bindParam(':titulo', $data['titulo'], PDO::PARAM_STR);
            $stmt->bindParam(':slug', $data['slug'], PDO::PARAM_STR);
            $stmt->bindParam(':texto_corto', $data['texto_corto'], PDO::PARAM_STR);
            $stmt->bindParam(':texto_largo', $data['texto_largo'], PDO::PARAM_STR);
            $stmt->bindParam(':nombre_imagen', $data['url_imagen'], PDO::PARAM_STR);
            $stmt->bindParam(':fecha_creacion', $data['fecha_creacion']);

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

    public function getAll()
    {
        try {

            $sql = "SELECT
                    b.id,
                    b.titulo,
                    b.texto_corto,
                    b.id_categoria,
                    c.nombre as 'categoria',
                    b.slug,
                    b.nombre_imagen
                    FROM blogs b
                    INNER JOIN categorias c ON c.id_categoria = b.id_categoria";

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
                    b.id,
                    b.titulo,
                    b.id_categoria,
                    c.nombre as 'categoria',
                    b.texto_corto,
                    b.texto_largo,
                    b.nombre_imagen
                    FROM blogs b
                    INNER JOIN categorias c ON c.id_categoria = b.id_categoria
                    WHERE b.id = :id";

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

            $sql = "UPDATE blogs b
                    SET
                    b.titulo = :titulo,
                    b.slug = :slug,
                    b.texto_corto = :texto_corto,
                    b.texto_largo = :texto_largo,
                    b.fecha_actualizacion = :fecha_actualizacion
                    WHERE b.id = :id";

            $stmt = $this->prepareQuery($sql);

            $stmt->bindParam(':titulo', $data['titulo'], PDO::PARAM_STR);
            $stmt->bindParam(':slug', $data['slug'], PDO::PARAM_STR);
            $stmt->bindParam(':texto_corto', $data['texto_corto'], PDO::PARAM_STR);
            $stmt->bindParam(':texto_largo', $data['texto_largo'], PDO::PARAM_STR);
            $stmt->bindParam(':fecha_actualizacion', $data['fecha_actualizacion']);
            $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);

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

            $sql = "DELETE 
                    FROM blogs
                    WHERE id = :id";

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
