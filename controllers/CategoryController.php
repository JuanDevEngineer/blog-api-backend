<?php

namespace Controllers;

use Models\Category;
use Services\CategoryService;

class CategoryController extends AppController
{
  protected $categoryService;

  public function __construct()
  {
    parent::__construct();
    $this->categoryService = new CategoryService();
  }

  public function create()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $data = $this->request();
      $category = new Category();
      $category->name = $data['name'];

      $response = $this->categoryService->create($category);

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

  public function findAll()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      echo json_encode([
        'data' => $this->categoryService->findAll()
      ]);
    } else {
      echo $this->methodAllowed();
    }
  }

  public function findById($id)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      echo json_encode([
        "category" => $this->categoryService->findById($id)
      ]);
    } else {
      echo $this->methodAllowed();
    }
  }

  public function update($id)
  {

    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

      $data = $this->request();
      $category = new Category();

      $category->id = $id;
      $category->name = $data['name'];

      $response = $this->categoryService->update($category);

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
