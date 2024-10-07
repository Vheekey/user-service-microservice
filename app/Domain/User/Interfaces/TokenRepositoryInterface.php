<?php

namespace App\Domain\User\Interfaces;

use App\Domain\User\Entities\User;

interface TokenRepositoryInterface
{
    public function createToken(User $user): string;

    public function verifyToken(string $token): bool;

    public function getTokenDetails(string $token): array|null;
}
