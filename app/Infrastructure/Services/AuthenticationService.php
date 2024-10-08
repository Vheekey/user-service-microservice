<?php

namespace App\Infrastructure\Services;

use App\Domain\User\Entities\User;
use App\Domain\User\Interfaces\AuthenticationServiceInterface;

class AuthenticationService implements AuthenticationServiceInterface
{
    public function hashPassword(string $password): string
    {
        return bcrypt($password);
    }


    public function generateToken(User $user, ?array $abilities): string
    {
        return $user->getUserModel()
            ->createToken($user->getEmail(), $abilities)
            ->plainTextToken;
    }
}
