<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Middleware;

use App\Domain\Exceptions\InvalidTokenException;
use App\Infrastructure\Security\JwtTokenService;

class JwtMiddleware
{
    public function __construct(
        private readonly JwtTokenService $tokenService,
    ) {}

    public function handle(): void
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

        if (empty($header)) {
            $this->respondUnauthorized('Token not found in request');
        }

        $parts = explode(' ', $header);

        if (count($parts) !== 2 || strtolower($parts[0]) !== 'bearer') {
            $this->respondUnauthorized('Authorization header must be: Bearer <token>');
        }

        $token = $parts[1];

        try {
            $this->tokenService->verify($token);
        } catch (InvalidTokenException $e) {
            $this->respondUnauthorized($e->getMessage());
        }
    }

    private function respondUnauthorized(string $message): never
    {
        header('HTTP/1.1 401 Unauthorized');
        header('Content-Type: application/json');

        echo json_encode([
            'status'  => 401,
            'success' => false,
            'msg'     => $message,
            'data'    => null,
        ]);

        exit;
    }
}
