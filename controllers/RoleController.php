<?php

namespace Controllers;

use Models\Role;
use Services\RoleService;

class RoleController extends AppController
{
  protected $rolService;

  public function __construct()
  {
    parent::__construct();
    $this->rolService = new RoleService();
  }

  public function create()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $data = $this->request();

      if (!isset($data['name'])) {
        echo $this->methodBadRequest('All fields are requireds, no recevied fields json');
        die();
      }

      if (empty($data['name'])) {
        echo $this->methodBadRequest('All fields are requireds');
        die();
      }

      $rol = new Role();
      $rol->name = $data['name'];

      $response = $this->rolService->create($rol);
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
      echo $this->methodOk("", $this->rolService->findAll());
    } else {
      echo $this->methodAllowed();
    }
  }

  public function findById($id)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      if (count($this->rolService->findById($id)) == 0) {
        echo $this->methodNotFound("Rol by Id {$id} not found");
        die();
      }

      echo $this->methodOk("", $this->rolService->findById($id));
    } else {
      echo $this->methodAllowed();
    }
  }

  public function delete($id)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
      $response = $this->rolService->delete($id);

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
