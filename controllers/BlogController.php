<?php

namespace Controllers;

use Models\Blog;
use Services\BlogService;

class BlogController extends AppController
{
  protected $blogService;

  public function __construct()
  {
    parent::__construct();
    $this->blogService = new BlogService();
  }

  public function create()
  {
    $path = "uploads/blogs/";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // if (isset($_FILES) && isset($_FILES['blog']['name'])) {
      //   $nombre_archivo = $_FILES['blog']['name'];
      //   $tipo = $_FILES['blog']['type'];
      //   $tmp_name = $_FILES['blog']['tmp_name'];
      //   $error =  $_FILES['blog']['error'];
      //   $size = $_FILES['blog']['size'];

      //   if ($this->validateFormatImage($tipo)) {

      //     if ($error === 0) {

      //       if ($this->validateSize($size)) {

      //         //separar la imagen  [nombre => 0] . ['jpg' => 1]
      //         $nombreext = explode('.', $nombre_archivo);
      //         // estraer el jpg
      //         $nombreacext = strtolower(end($nombreext));

      //         // cambio de el nombre
      //         $nombre_guardar = uniqid('', true) . "." . $nombreacext;

      //         $nombre_imagen = dirname(__DIR__) . "\\" . $path . $nombre_guardar;

      //         move_uploaded_file($tmp_name, $path . $nombre_guardar);
      //       } else {
      //         echo json_encode(array(
      //           'success' => false,
      //           'msg' => 'error en el tamaño',
      //         ));
      //       }
      //     } else {
      //       echo json_encode(array(
      //         'success' => false,
      //         'msg' => 'error al subir el archivo',
      //       ));
      //     }
      //   } else {
      //     echo json_encode(array(
      //       'success' => false,
      //       'msg' => 'error en tipo',
      //     ));
      //   }
      // }
      $data = $this->request();
      $blog = new Blog();
      $blog->id_categoria = $data['id_categoria'];
      $blog->titulo = $data['titulo'];
      $blog->slug = $data['slug'];
      $blog->texto_corto = $data['texto_corto'];
      $blog->texto_largo = $data['texto_largo'];
      $blog->url_imagen = $nombre_imagen ?? '';
      $blog->fecha_creacion = date("Y-m-d H:i:s", time());

      $response = $this->blogService->create($blog);

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
      echo json_encode(array(
        'data' => $this->blogService->findAll()
      ));
    } else {
      echo $this->methodAllowed();
    }
  }

  public function findById($id)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $response = $this->blogService->findById($id);
      echo json_encode(array(
        'data' => $response
      ));
    } else {
      echo $this->methodAllowed();
    }
  }

  public function update($id)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
      $data = $this->request();
      $blog = new Blog();
      $blog->id = $id;
      $blog->titulo = $data['titulo'];
      $blog->slug = $data['slug'];
      $blog->texto_corto = $data['texto_corto'];
      $blog->fecha_actualizacion = date("Y-m-d H:i:s", time());

      $response = $this->blogService->update($blog);

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
      $response = $this->blogService->delete($id);

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
