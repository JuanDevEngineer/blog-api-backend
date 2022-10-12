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
    if ($this->allowRequestMethod('POST')) {
      $data = $this->request();

      if (!isset($data['name']) || !isset($data['email']) || !isset($data['password']) || !isset($data['numberPhone']) || !isset($data['rolId'])) {
        echo $this->methodBadRequest('All fields are requireds, no recevied fields json');
        die();
      }

      if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        echo $this->methodBadRequest('Enter a valid email address');
        die();
      }

      if (strlen($data['password']) < 8) {
        echo $this->methodBadRequest('Minimun caracters is 8');
        die();
      }

      if (strlen($data['numberPhone']) < 10) {
        echo $this->methodBadRequest('Minimun numbers is 10');
        die();
      }

      if (empty($data['name']) || empty($data['email']) || empty($data['password']) || empty($data['numberPhone']) || $data['rolId'] < 1) {
        echo $this->methodBadRequest('All fields are requireds');
        die();
      }

      $user = new User();
      $user->name = $data['name'];
      $user->email = $data['email'];
      $user->password = $data['password'];
      $user->numberPhone = $data['numberPhone'];
      $user->typeUser = $data['rolId']; // Admin, user, etc...

      $response = $this->userService->create($user);
      if ($response['success']) {
        echo $this->methodCreated($response['msg']);
      } else {
        echo $this->methodBadRequest($response['msg']);
      }
    } else {
      echo $this->methodAllowed();
    }
  }

  public function findAll()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      echo $this->methodOk("", $this->userService->findAll());
    } else {
      echo $this->methodAllowed();
    }
  }

  public function findById($id)
  {

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      if (count($this->userService->findById($id)) === 0) {
        echo $this->methodNotFound("User by id {$id} not found");
        die();
      }
      echo $this->methodOk("", $this->userService->findById($id));
    } else {
      echo $this->methodAllowed();
    }
  }

  public function update($id)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
      $data = $this->request();

      if (!isset($data['name']) || !isset($data['numberPhone']) || !isset($data['rolId'])) {
        echo $this->methodBadRequest('All fields are requireds, no recevied fields json');
        die();
      }

      if (strlen($data['numberPhone']) < 10) {
        echo $this->methodBadRequest('Minimun numbers is 10');
        die();
      }

      if (empty($data['name']) || empty($data['numberPhone']) || $data['rolId'] < 1) {
        echo $this->methodBadRequest('All fields are requireds');
        die();
      }

      $user = new User();
      $user->id = $id;
      $user->name = $data['name'];
      $user->numberPhone = $data['numberPhone'];
      $user->state = $data['state'];
      $user->typeUser = $data['rolId']; // Admin, user, etc...

      $response = $this->userService->update($user);

      if ($response['success']) {
        echo $this->methodOk($response['msg']);
      } else {
        echo $this->methodBadRequest($response['msg']);
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
        echo $this->methodOk($response['msg']);
      } else {
        echo $this->methodBadRequest($response['msg']);
      }
    } else {
      echo $this->methodAllowed();
    }
  }
}
