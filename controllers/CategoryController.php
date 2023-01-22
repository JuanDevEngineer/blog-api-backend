<?php

namespace Controllers;

use Daos\CategoryDao;
use Models\Category;
use Services\CategoryService;

class CategoryController extends AppController
{
	protected CategoryService $categoryService;
	
	public function __construct()
	{
		parent::__construct();
		$this->categoryService = new CategoryService(new CategoryDao);
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
			
			$category = new Category();
			$category->name = $data['name'];
			
			$response = $this->categoryService->create($category);
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
			echo $this->methodOk("", $this->categoryService->findAll());
		} else {
			echo $this->methodAllowed();
		}
	}
	
	public function findById($id)
	{
		if ($_SERVER['REQUEST_METHOD'] === 'GET') {
			
			if (count($this->categoryService->findById($id)) == 0) {
				echo $this->methodNotFound("category by Id {$id} not found");
				die();
			}
			
			echo $this->methodOk("", $this->categoryService->findById($id));
		} else {
			echo $this->methodAllowed();
		}
	}
	
	public function update($id)
	{
		if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
			$data = $this->request();
			
			if (!isset($data['name'])) {
				echo $this->methodBadRequest('All fields are requireds, no recevied fields json');
				die();
			}
			
			if (empty($data['name'])) {
				echo $this->methodBadRequest('All fields are requireds');
				die();
			}
			
			$category = new Category();
			
			$category->id = $id;
			$category->name = $data['name'];
			
			$response = $this->categoryService->update($category);
			
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
			$response = $this->categoryService->delete($id);
			
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
