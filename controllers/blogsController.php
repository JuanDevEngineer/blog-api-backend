<?php

require_once 'controllers/appController.php';
require_once './models/blogsmodel.php';

class Blogs extends App
{

    protected $blogs;

    public function __construct()
    {
        parent::__construct();
        $this->blogs = new BlogsModel();
    }

    public function create()
    {
        $path = "uploads/blogs/";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (isset($_FILES)) {
                $nombre_archivo = $_FILES['blog']['name'];
                $tipo = $_FILES['blog']['type'];
                $tmp_name = $_FILES['blog']['tmp_name'];
                $error =  $_FILES['blog']['error'];
                $size = $_FILES['blog']['size'];

                if ($this->validateFormatImage($tipo)) {

                    if ($error === 0) {

                        if ($this->validateSize($size)) {

                            //separar la imagen  [nombre => 0] . ['jpg' => 1]
                            $nombreext = explode('.', $nombre_archivo);
                            // estraer el jpg
                            $nombreacext = strtolower(end($nombreext));

                            // cambio de el nombre
                            $nombre_guardar = uniqid('', true) . "." . $nombreacext;

                            $nombre_imagen = dirname(__DIR__) . "\\" . $path . $nombre_guardar;

                            move_uploaded_file($tmp_name, $path . $nombre_guardar);
                        } else {
                            echo json_encode(array(
                                'success' => false,
                                'msg' => 'error en el tamaño',
                            ));
                        }
                    } else {
                        echo json_encode(array(
                            'success' => false,
                            'msg' => 'error al subir el archivo',
                        ));
                    }
                } else {
                    echo json_encode(array(
                        'success' => false,
                        'msg' => 'error en tipo',
                    ));
                }
            }
            extract($_REQUEST);
            $blog = array(
                'id_categoria' => $id_categoria,
                'titulo' => $titulo,
                'slug' => $slug,
                'texto_corto' => $texto_corto,
                'texto_largo' => $texto_largo,
                'fecha_creacion' => $fecha_creacion,
                'url_imagen' => $nombre_imagen
            );

            $response = $this->blogs->create($blog);

            if ($response['success']) {
                echo json_encode(array(
                    'success' => true,
                    'msg' => $response['msg'],
                ));
            } else {
                echo json_encode(array(
                    'success' => false,
                    'msg' => $response['msg'],
                ));
            }
        } else {
            echo json_encode(array(
                'success' => 404,
                'msg' => 'error en el metodo de envio',
            ));
        }
    }

    public function getAll()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $response = $this->blogs->getAll();

            echo json_encode(array(
                'data' => $response
            ));
        } else {
            echo json_encode(array(
                'status' => 404,
                'msg' => 'error en el metodo de envio',
            ));
        }
    }

    public function getOne($param)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $param_id = $param[0];
            $response = $this->blogs->getOne($param_id);
            echo json_encode(array(
                'data' => $response
            ));
        } else {
            echo json_encode(array(
                'success' => 404,
                'msg' => 'error en el metodo de envio',
            ));
        }
    }


    public function update($param)
    {

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

            $data = json_decode(file_get_contents("php://input"));

            $param_id = "";

            if (isset($param)) {
                $param_id = $param[0];
            }

            $blog = array(
                "id" => $param_id,
                "titulo" => $data->titulo,
                "slug" => $data->slug,
                "texto_corto" => $data->texto_corto,
                "fecha_actualizacion" => $data->fecha_actualizacion,
            );


            $response = $this->blogs->update($blog);

            if ($response['success']) {
                echo json_encode(array(
                    'success' => true,
                    'msg' => $response['msg'],
                ));
            } else {
                echo json_encode(array(
                    'success' => false,
                    'msg' => $response['msg'],
                ));
            }
        } else {
            echo json_encode(array(
                'success' => 404,
                'msg' => 'error en el metodo de envio',
            ));
        }
    }

    public function delete($param)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

            $param_id = "";

            if (isset($param)) {
                $param_id = $param[0];
            }

            $response = $this->blogs->delete($param_id);

            if ($response['success']) {
                echo json_encode(array(
                    'success' => true,
                    'msg' => $response['msg'],
                ));
            } else {
                echo json_encode(array(
                    'success' => false,
                    'msg' => $response['msg'],
                ));
            }
        } else {
            echo json_encode(array(
                'success' => 404,
                'msg' => 'error en el metodo de envio',
            ));
        }
    }
}
