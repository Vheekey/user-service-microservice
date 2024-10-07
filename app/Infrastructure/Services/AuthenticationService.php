<?php

namespace App\Infrastructure\Services;

use App\Domain\User\Interfaces\AuthenticationServiceInterface;

class AuthenticationService implements AuthenticationServiceInterface
{
    public function hashPassword(string $password): string
    {
        return bcrypt($password);
    }
}
