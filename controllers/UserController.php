<?php

namespace Controllers;

class UserController extends AppController
{
  protected $userService;

  public function __construct()
  {
    parent::__construct();
  }

  public function create()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $data = json_decode(file_get_contents("php://input"));

      $usuario = array(
        "nombre" => $data->nombre,
        "correo" => $data->correo,
        "password" => $data->password,
        "numero_movil" => $data->numeroMovil,
        "tipo_usuario" => $data->tipoUsuario,
        "fecha_creacion" => $data->fechaCreacion
      );

      $response = $this->user->create($usuario);

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

      $response = $this->user->getAll();

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
      $response = $this->user->getOne($param_id);
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
        "id" => $param_id,
        "nombre" => $data->nombre,
        "numero_movil" => $data->numeroMovil,
        "tipo_usuario" => $data->tipoUsuario,
        "fechaActualizacion" => $data->fechaActualizacion
      );


      $response = $this->user->update($usuario);

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

      $response = $this->user->delete($param_id);

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
