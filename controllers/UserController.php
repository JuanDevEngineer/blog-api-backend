<?php

namespace Controllers;

use Models\User;
use Services\UserService;

class UserController extends AppController
{
  protected $userService;

  public function __construct()
  {
    parent::__construct();
    $this->userService = new UserService();
  }

  public function create()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $data = $this->request();

      $user = new User();
      $user->name = $data['nombre'];
      $user->email = $data['correo'];
      $user->password = $data['password'];
      $user->numberPhone = $data['numeroMovil'];
      $user->typeUser = $data['tipoUsuario']; // Admin, user

      $response = $this->userService->create($user);

      if ($response['success']) {
        http_response_code(201);
        echo json_encode(array(
          'status' => http_response_code(201),
          'success' => true,
          'msg' => $response['msg'],
        ));
      } else {
        http_response_code(400);
        echo json_encode(array(
          'status' => http_response_code(400),
          'success' => false,
          'msg' => $response['msg'],
        ));
      }
    } else {
      echo $this->methodAllowed();
    }
  }

  public function findAll()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      echo json_encode([
        'data' => $this->userService->findAll()
      ]);
    } else {
      echo $this->methodAllowed();
    }
  }

  public function findById($id)
  {

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      echo json_encode([
        'data' => $this->userService->findById($id)
      ]);
    } else {
      echo $this->methodAllowed();
    }
  }

  public function update($id)
  {

    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

      $data = $this->request();

      $user = new User();
      $user->name = $data['nombre'];
      $user->email = $data['correo'];
      $user->numberPhone = $data['numeroMovil'];
      $user->typeUser = $data['tipoUsuario']; // Admin, user
      $user->name = $data['nombre'];
      // $usuario = array(
      //   "id" => $param_id,
      //   "nombre" => $data->nombre,
      //   "numero_movil" => $data->numeroMovil,
      //   "tipo_usuario" => $data->tipoUsuario,
      // );


      $response = $this->userService->update($user);

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
      echo $this->methodAllowed();
    }
  }

  public function delete($id)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
      $response = $this->userService->delete($id);
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
      echo $this->methodAllowed();
    }
  }
}
