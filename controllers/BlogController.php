<?php

namespace Controllers;

use Config\Config;
use Daos\BlogDao;
use Models\Blog;
use Services\BlogService;

class BlogController extends AppController
{
	protected BlogService $blogService;
	
	public function __construct()
	{
		parent::__construct();
		$this->blogService = new BlogService(new BlogDao);
	}
	
	public function create($image_path)
	{
		if ($this->allowRequestMethod('POST')) {
			if (!$this->acceptsJson()) {
				$image_path = $this->saveFile();
				$blog = $this->getBlog($_POST, $image_path);
			} else {
				$data = $this->request();
				$blog = $this->getBlog($data, $image_path);
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
		if (isset($_FILES) && isset($_FILES['blog']['name'])) {
			$nameFile = $_FILES['blog']['name'];
			$type = $_FILES['blog']['type'];
			$tmpName = $_FILES['blog']['tmp_name'];
			$error = $_FILES['blog']['error'];
			$size = $_FILES['blog']['size'];
			
			if ($this->validateFormatImage($type)) {
				
				if ($error === 0) {
					if ($this->validateSize($size)) {
						//separate the image  [name => 0] . ['jpg' => 1]
						$nameExt = explode('.', $nameFile);
						// extract the jpg
						$nameAcExt = strtolower(end($nameExt));
						
						// change of the name
						$nameSave = uniqid('', true) . "." . $nameAcExt;
						
						$imagePath = Config::PUBLIC_PATH . $nameSave;
						move_uploaded_file($tmpName, Config::PUBLIC_PATH . $nameSave);
						
						return $imagePath;
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
	
	private function getBlog(mixed $data, $image_path): Blog
	{
		$blog = new Blog();
		$blog->category_id = $data['category_id'];
		$blog->title = $data['title'];
		$blog->slug = $data['slug'];
		$blog->text_short = $data['text_short'];
		$blog->text_large = $data['text_large'];
		$blog->path_image = $image_path ?? '';
		return $blog;
	}
}
