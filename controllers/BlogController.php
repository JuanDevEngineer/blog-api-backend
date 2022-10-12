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
    if ($this->allowRequestMethod('POST')) {
      if (!$this->acceptsJson()) {
        $image_path = $this->saveFile();
        $blog = new Blog();
        $blog->category_id = $_POST['category_id'];
        $blog->title = $_POST['title'];
        $blog->slug = $_POST['slug'];
        $blog->text_short = $_POST['text_short'];
        $blog->text_large = $_POST['text_large'];
        $blog->path_image = $image_path ?? '';
      } else {
        $data = $this->request();
        $blog = new Blog();
        $blog->category_id = $data['category_id'];
        $blog->title = $data['title'];
        $blog->slug = $data['slug'];
        $blog->text_short = $data['text_short'];
        $blog->text_large = $data['text_large'];
        $blog->path_image = $image_path ?? '';
      }

      $response = $this->blogService->create($blog);

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
    if ($this->allowRequestMethod()) {
      echo $this->methodOk("", $this->blogService->findAll());
    } else {
      echo $this->methodAllowed();
    }
  }

  public function findById($id)
  {
    if ($this->allowRequestMethod()) {
      if (count($this->blogService->findById($id)) === 0) {
        echo $this->methodNotFound("Blog by id {$id} not found");
        die();
      }
      echo $this->methodOk("", $this->blogService->findById($id));
    } else {
      echo $this->methodAllowed();
    }
  }

  public function update($id)
  {
    if ($this->allowRequestMethod('PUT')) {
      $data = $this->request();
      $blog = new Blog();
      $blog->id = $id;
      $blog->category_id = $data['category_id'];
      $blog->title = $data['title'];
      $blog->slug = $data['slug'];
      $blog->text_short = $data['text_short'];
      $blog->text_large = $data['text_large'];

      $response = $this->blogService->update($blog);

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
    if ($this->allowRequestMethod('DELETE')) {
      $response = $this->blogService->delete($id);
      if ($response['success']) {
        echo $this->methodOk($response['msg']);
      } else {
        echo $this->methodBadRequest($response['msg']);
      }
    } else {
      echo $this->methodAllowed();
    }
  }

  private function saveFile()
  {
    $path = "public/uploads/blogs/";
    if (isset($_FILES) && isset($_FILES['blog']['name'])) {
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

            $image_path = $path . $nombre_guardar;
            move_uploaded_file($tmp_name, $path . $nombre_guardar);

            return $image_path;
          } else {
            echo $this->methodBadRequest('Error in size file');
            die();
          }
        } else {
          echo $this->methodBadRequest('Error to upload file');
          die();
        }
      } else {
        echo $this->methodBadRequest('Error in the type file');
        die();
      }
    }
  }
}
