<?php

namespace Controllers;

use Models\User;
use Services\UserService;
use Services\JwtService;
use Daos\UserDao;

class AuthController extends AppController
{
	private UserService $userService;
	
	public function __construct()
	{
		parent::__construct();
		$this->userService = new UserService(new UserDao);
	}
	
	public function login()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$data = $this->request();
			if (!isset($data['email']) || !isset($data['password'])) {
				$this->methodBadRequest();
				echo json_encode([
					'status' => http_response_code(400),
					'message' => 'All fields are required, no received fields json'
				]);
				die();
			}
			
			if (empty($data['email']) || empty($data['password'])) {
				$this->methodBadRequest();
				echo json_encode([
					'status' => http_response_code(400),
					'message' => 'All fields are required'
				]);
				die();
			}
			
			$user = new User();
			$user->email = $data['email'];
			$user->password = $data['password'];
			
			$this->extracted($user, $data['password']);
		} else {
			echo $this->methodAllowed();
		}
	}
	
	public function register()
	{
		if ($this->allowRequestMethod('POST')) {
			$data = $this->request();
			if (!isset($data['email']) || !isset($data['password']) || !isset($data['confirmPassword']) || !isset($data['numberPhone'])) {
				echo $this->methodBadRequest('All fields are required, no received fields json');
				die();
			}
			
			if (empty($data['email']) || empty($data['password']) || empty($data['confirmPassword']) || empty($data['numberPhone'])) {
				echo $this->methodBadRequest('All fields are required');
				die();
			}
			
			$user = new User();
			$user->name = $data['name'];
			$user->email = $data['email'];
			$user->password = $data['password'];
			$user->numberPhone = $data['numberPhone'];
			$user->typeUser = 2; // USER
			
			$this->extracted($user, $data['password']);
		} else {
			echo $this->methodAllowed();
		}
	}
	
	private function extracted(User $user, $password): void
	{
		$existEmail = $this->userService->existUser($user);
		
		if (!$existEmail) {
			echo $this->methodBadRequest('Error in your email or password');
			die();
		}
		
		$userResponse = $this->userService->findByEmail($user);
		$hash = $userResponse['data']['password'];
		if (!$this->validatePassword($password, $hash)) {
			echo $this->methodBadRequest('Error in your email or password');
			die();
		}
		
		$jwtService = new JwtService($userResponse['data']['id']);
		echo $this->methodOk(
			"",
			[
				'user' => [
					'name' => $userResponse['data']['name'],
					'email' => $userResponse['data']['email'],
					'rol' => $userResponse['data']['rol'],
				],
				'token' => $jwtService->generateToken()
			],
		);
	}
}
