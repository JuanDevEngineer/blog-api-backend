<?php

namespace Controllers;

use Models\User;
use Services\JwtService;
use Services\UserService;

class AuthController extends AppController
{
  private $userService;

  public function __construct()
  {
    parent::__construct();
    $this->userService = new UserService();
  }

  public function login()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $data = $this->request();
      if (!isset($data['email']) || !isset($data['password'])) {
        $this->methodBadRequest();
        echo json_encode([
          'status' => http_response_code(400),
          'message' => 'All fields are requireds, no recevied fields json'
        ]);
        die();
      }

      if (empty($data['email']) || empty($data['password'])) {
        $this->methodBadRequest();
        echo json_encode([
          'status' => http_response_code(400),
          'message' => 'All fields are requireds'
        ]);
        die();
      }

      $user = new User();
      $user->email = $data['email'];
      $user->password = $data['password'];

      $existEmail = $this->userService->existUser($user);

      if (!$existEmail) {
        $this->methodBadRequest();
        echo json_encode([
          'status' => http_response_code(400),
          'message' => 'Error in your email or password',
          'data' => NULL
        ]);
        die();
      }

      $userResponse = $this->userService->findByEmail($user);
      $hash = $userResponse['data']['password'];
      if (!$this->validatePassword($data['password'], $hash)) {
        $this->methodBadRequest();
        echo json_encode([
          'status' => http_response_code(400),
          'message' => 'Error in your password',
          'data' => NULL
        ]);
        die();
      }

      $jwtService = new JwtService($userResponse['data']['id']);
      $this->methodOk();
      echo json_encode([
        'data' => [
          'name' => $userResponse['data']['name'],
          'email' => $userResponse['data']['email'],
          'rol' => $userResponse['data']['rol'],
        ],
        'token' => $jwtService->generateToken()
      ]);
    } else {
      echo $this->methodAllowed();
    }
  }

  public function register()
  {
    echo json_encode([
      'message' => 'Hello world'
    ]);
  }
}
