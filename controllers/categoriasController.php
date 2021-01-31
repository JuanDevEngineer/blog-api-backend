<?php

require_once 'controllers/appController.php';
require_once './models/categoriasmodel.php';

class Categorias extends app {

    protected $categorias;

    public function __construct()
    {
        $this->categorias = new CategoriasModel();
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents("php://input"));

            $response = $this->categorias->create($data->categoria);

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

            $response = $this->categorias->getAll();

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
            $response = $this->categorias->getOne($param_id);
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

            $usuario = array(
                "id_categoria" => $param_id,
                "nombre" => $data->nombre,
            );


            $response = $this->categorias->update($usuario);

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

            $response = $this->categorias->delete($param_id);

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