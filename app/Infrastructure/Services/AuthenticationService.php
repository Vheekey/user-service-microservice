<?php

namespace App\Infrastructure\Services;

use App\Domain\User\Entities\User;
use App\Domain\User\Interfaces\AuthenticationServiceInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthenticationService implements AuthenticationServiceInterface
{
    public function hashPassword(string $password): string
    {
        return bcrypt($password);
    }

    public function generateToken(User $user, ?array $abilities): string
    {
        $sanctumToken = $user->getUserModel($user->getEmail())
            ->createToken($user->getEmail(), $abilities);
        $expiry = time() + 52000;

        $jwtToken = $this->generateJwt($user, $expiry, $abilities);

        $sanctumToken->accessToken->update([
            'jwt' => $jwtToken,
            'expires_at' => $expiry
        ]);

        return $jwtToken;
    }

    private function generateJwt(User $user, int $expiry, ?array $abilities = []): string
    {
        $payload = [
            'iss' => config('app.name'), // Issuer
            'sub' => $user->getId(), //subject
            'email' => $user->getEmail(),
            'abilities' => $abilities,
            'iat' => time(),          // Issued at
            'exp' => $expiry
        ];

        return JWT::encode($payload, env('JWT_SECRET'), 'HS256');
    }

    public function decodeJwtToken(string $token): ?object
    {
        try {
            return JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
        } catch (NotFoundHttpException $e) {
            return null;
        }
    }

    public function isValidJwtToken(string $token): bool
    {
        $jwtDecoded = $this->decodeJwtToken($token);

        if (is_null($jwtDecoded)) {
            return false;
        }

        return $jwtDecoded->exp > time();
    }
}
