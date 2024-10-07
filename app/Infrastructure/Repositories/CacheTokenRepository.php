<?php

namespace App\Infrastructure\Repositories;

use App\Domain\User\Entities\User;
use App\Domain\User\Interfaces\TokenRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Random\RandomException;

class CacheTokenRepository implements TokenRepositoryInterface
{
    private const TTL = 1200;

    /**
     * @throws RandomException
     */
    public function createToken(User $user): string
    {
        $token = $this->generateToken();
        $key = $this->makeRedisKey($token);

        Cache::put($key, $user, self::TTL);

        return $token;
    }

    public function getTokenDetails(string $token): array
    {
        return Cache::get($this->makeRedisKey($token));
    }

    /**
     * @throws RandomException
     */
    private function generateToken(): string
    {
        return strtoupper(bin2hex(random_bytes(3)));
    }

    private function makeRedisKey(string $token): string
    {
        return 'token_' . $token;
    }

    public function verifyToken(string $token): bool
    {
        return Cache::has($this->makeRedisKey($token));
    }
}
