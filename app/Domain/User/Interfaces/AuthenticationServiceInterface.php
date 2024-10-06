<?php

namespace App\Domain\User\Interfaces;

interface AuthenticationServiceInterface
{
    public function hashPassword(string $password): string;
}
