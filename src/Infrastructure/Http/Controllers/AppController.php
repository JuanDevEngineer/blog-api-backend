<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

class AppController
{
    protected function request(): ?array
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    protected function isJson(): bool
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        return strtolower($contentType) === 'application/json';
    }

    protected function methodOk(string $message = '', mixed $data = null): string
    {
        header('HTTP/1.1 200 OK');
        return json_encode([
            'status'  => 200,
            'success' => true,
            'msg'     => $message,
            'data'    => $data,
        ]);
    }

    protected function methodCreated(string $message = '', mixed $data = null): string
    {
        header('HTTP/1.1 201 Created');
        return json_encode([
            'status'  => 201,
            'success' => true,
            'msg'     => $message,
            'data'    => $data,
        ]);
    }

    protected function methodBadRequest(string $message = ''): string
    {
        header('HTTP/1.1 400 Bad Request');
        return json_encode([
            'status'  => 400,
            'success' => false,
            'msg'     => $message,
            'data'    => null,
        ]);
    }

    protected function methodUnauthorized(string $message = ''): string
    {
        header('HTTP/1.1 401 Unauthorized');
        return json_encode([
            'status'  => 401,
            'success' => false,
            'msg'     => $message,
            'data'    => null,
        ]);
    }

    protected function methodNotFound(string $message = ''): string
    {
        header('HTTP/1.1 404 Not Found');
        return json_encode([
            'status'  => 404,
            'success' => false,
            'msg'     => $message,
            'data'    => null,
        ]);
    }

    protected function methodConflict(string $message = ''): string
    {
        header('HTTP/1.1 409 Conflict');
        return json_encode([
            'status'  => 409,
            'success' => false,
            'msg'     => $message,
            'data'    => null,
        ]);
    }

    protected function methodErrorServer(string $message = ''): string
    {
        header('HTTP/1.1 500 Internal Server Error');
        return json_encode([
            'status'  => 500,
            'success' => false,
            'msg'     => $message,
            'data'    => null,
        ]);
    }
}
