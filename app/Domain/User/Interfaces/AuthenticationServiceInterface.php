<?php

namespace App\Domain\User\Interfaces;

use App\Domain\User\Entities\User;

interface AuthenticationServiceInterface
{
    public function hashPassword(string $password): string;

    public function generateToken(User $user, ?array $abilities): string;

    public function verifyToken(User $user, ?array $abilities): bool;
}
