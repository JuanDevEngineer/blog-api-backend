<?php

declare(strict_types=1);

use Bramus\Router\Router;

// ── Infraestructura ──────────────────────────────────────────────────────────
use App\Infrastructure\Database\DatabaseConnection;
use App\Infrastructure\Security\JwtTokenService;
use App\Infrastructure\Storage\LocalFileStorage;
use App\Infrastructure\Http\Middleware\JwtMiddleware;

// ── Repositorios ─────────────────────────────────────────────────────────────
use App\Infrastructure\Persistence\MySQL\BlogRepository;
use App\Infrastructure\Persistence\MySQL\UserRepository;
use App\Infrastructure\Persistence\MySQL\CategoryRepository;
use App\Infrastructure\Persistence\MySQL\RoleRepository;

// ── Controllers ──────────────────────────────────────────────────────────────
use App\Infrastructure\Http\Controllers\AuthController;
use App\Infrastructure\Http\Controllers\BlogController;
use App\Infrastructure\Http\Controllers\CategoryController;
use App\Infrastructure\Http\Controllers\UserController;
use App\Infrastructure\Http\Controllers\RoleController;

// ── Use Cases: Auth ───────────────────────────────────────────────────────────
use App\Application\UseCases\Auth\LoginUseCase;
use App\Application\UseCases\Auth\RegisterUseCase;

// ── Use Cases: Blog ───────────────────────────────────────────────────────────
use App\Application\UseCases\Blog\CreateBlogUseCase;
use App\Application\UseCases\Blog\DeleteBlogUseCase;
use App\Application\UseCases\Blog\FindAllBlogsUseCase;
use App\Application\UseCases\Blog\FindBlogByIdUseCase;
use App\Application\UseCases\Blog\UpdateBlogUseCase;

// ── Use Cases: Category ───────────────────────────────────────────────────────
use App\Application\UseCases\Category\CreateCategoryUseCase;
use App\Application\UseCases\Category\DeleteCategoryUseCase;
use App\Application\UseCases\Category\FindAllCategoriesUseCase;
use App\Application\UseCases\Category\FindCategoryByIdUseCase;
use App\Application\UseCases\Category\UpdateCategoryUseCase;

// ── Use Cases: User ───────────────────────────────────────────────────────────
use App\Application\UseCases\User\CreateUserUseCase;
use App\Application\UseCases\User\DeleteUserUseCase;
use App\Application\UseCases\User\FindAllUsersUseCase;
use App\Application\UseCases\User\FindUserByIdUseCase;
use App\Application\UseCases\User\UpdateUserUseCase;

// ── Use Cases: Role ───────────────────────────────────────────────────────────
use App\Application\UseCases\Role\CreateRoleUseCase;
use App\Application\UseCases\Role\DeleteRoleUseCase;
use App\Application\UseCases\Role\FindAllRolesUseCase;
use App\Application\UseCases\Role\FindRoleByIdUseCase;
use App\Application\UseCases\Role\UpdateRoleUseCase;

// ── CORS ──────────────────────────────────────────────────────────────────────
$origins = '';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, PATCH, DELETE');
header('Access-Control-Max-Age: 3600');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

// Preflight CORS: el browser envía OPTIONS antes de POST/PUT — se responde sin token
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('HTTP/1.1 200 OK');
    exit;
}

// ── Composition Root ──────────────────────────────────────────────────────────

$db           = DatabaseConnection::getInstance();
$tokenService = new JwtTokenService();
$fileStorage  = new LocalFileStorage();
$middleware   = new JwtMiddleware($tokenService);

$blogRepo     = new BlogRepository($db);
$userRepo     = new UserRepository($db);
$categoryRepo = new CategoryRepository($db);
$roleRepo     = new RoleRepository($db);

$authController = new AuthController(
    new LoginUseCase($userRepo, $tokenService),
    new RegisterUseCase($userRepo),
);

$blogController = new BlogController(
    new CreateBlogUseCase($blogRepo, $categoryRepo),
    new FindAllBlogsUseCase($blogRepo),
    new FindBlogByIdUseCase($blogRepo),
    new UpdateBlogUseCase($blogRepo, $categoryRepo),
    new DeleteBlogUseCase($blogRepo),
    $fileStorage,
);

$categoryController = new CategoryController(
    new CreateCategoryUseCase($categoryRepo),
    new FindAllCategoriesUseCase($categoryRepo),
    new FindCategoryByIdUseCase($categoryRepo),
    new UpdateCategoryUseCase($categoryRepo),
    new DeleteCategoryUseCase($categoryRepo),
);

$userController = new UserController(
    new CreateUserUseCase($userRepo),
    new FindAllUsersUseCase($userRepo),
    new FindUserByIdUseCase($userRepo),
    new UpdateUserUseCase($userRepo),
    new DeleteUserUseCase($userRepo),
);

$roleController = new RoleController(
    new CreateRoleUseCase($roleRepo),
    new FindAllRolesUseCase($roleRepo),
    new FindRoleByIdUseCase($roleRepo),
    new UpdateRoleUseCase($roleRepo),
    new DeleteRoleUseCase($roleRepo),
);

// ── Router ────────────────────────────────────────────────────────────────────

$router = new Router();

// Auth (público — sin middleware)
$router->post('/auth/sign-in', fn() => $authController->login());
$router->post('/auth/sign-up', fn() => $authController->register());

// Middleware JWT — protege todos los endpoints /api/*
$router->before('GET|POST|PUT|DELETE', '/api/.*', fn() => $middleware->handle());

// Blog
$router->get('/api/blogs',      fn()      => $blogController->findAll());
$router->get('/api/blogs/{id}', fn($id)   => $blogController->findById((int) $id));
$router->post('/api/blogs',      fn()      => $blogController->create());
$router->put('/api/blogs/{id}', fn($id)   => $blogController->update((int) $id));
$router->delete('/api/blogs/{id}', fn($id)   => $blogController->delete((int) $id));

// Category
$router->get('/api/categories',      fn()    => $categoryController->findAll());
$router->get('/api/categories/{id}', fn($id) => $categoryController->findById((int) $id));
$router->post('/api/categories',      fn()    => $categoryController->create());
$router->put('/api/categories/{id}', fn($id) => $categoryController->update((int) $id));
$router->delete('/api/categories/{id}', fn($id) => $categoryController->delete((int) $id));

// User
$router->get('/api/users',      fn()    => $userController->findAll());
$router->get('/api/users/{id}', fn($id) => $userController->findById((int) $id));
$router->post('/api/users',      fn()    => $userController->create());
$router->put('/api/users/{id}', fn($id) => $userController->update((int) $id));
$router->delete('/api/users/{id}', fn($id) => $userController->delete((int) $id));

// Role
$router->get('/api/roles',      fn()    => $roleController->findAll());
$router->get('/api/roles/{id}', fn($id) => $roleController->findById((int) $id));
$router->post('/api/roles',      fn()    => $roleController->create());
$router->put('/api/roles/{id}', fn($id) => $roleController->update((int) $id));
$router->delete('/api/roles/{id}', fn($id) => $roleController->delete((int) $id));

// 404
$router->set404('/api(/.*)?', function () {
    header('HTTP/1.1 404 Not Found');
    echo json_encode([
        'status'  => 404,
        'success' => false,
        'msg'     => 'Route not found',
        'data'    => null,
    ]);
});

$router->run();
