<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use App\Domain\Exceptions\InvalidTokenException;
use App\Domain\Ports\Output\TokenServicePort;

class JwtTokenService implements TokenServicePort
{
    private string $secretKey;
    private string $algorithm  = 'HS256';
    private int    $expiration = 3600;

    public function __construct()
    {
        // BUG FIX: secret key solo desde .env — nunca desde código fuente
        if (empty($_ENV['SECRET_KEY_PRIVATE'])) {
            throw new \RuntimeException('SECRET_KEY_PRIVATE is not defined in .env');
        }

        $this->secretKey = $_ENV['SECRET_KEY_PRIVATE'];
    }

    public function generate(int $userId): string
    {
        $now = time();

        $payload = [
            'sub' => $userId,
            'iss' => $_ENV['APP_URL'] ?? 'http://localhost',
            'iat' => $now,
            'exp' => $now + $this->expiration,
        ];

        return JWT::encode($payload, $this->secretKey, $this->algorithm);
    }

    public function verify(string $token): void
    {
        try {
            JWT::decode($token, new Key($this->secretKey, $this->algorithm));
        } catch (ExpiredException) {
            throw new InvalidTokenException('Token has expired');
        } catch (BeforeValidException) {
            throw new InvalidTokenException('Token is not yet valid');
        } catch (SignatureInvalidException) {
            throw new InvalidTokenException('Token signature is invalid');
        } catch (\Exception $e) {
            throw new InvalidTokenException($e->getMessage());
        }
    }
}
