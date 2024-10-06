<?php

namespace App\Shared\Enums;

enum RoleEnum: int
{
    case USER = 1;
    case ADMIN = 2;

    public static function all(): array
    {
        return array_map(fn($role) => [
            'id' => $role->value,
            'name' => $role->name
        ], self::cases());
    }

    public static function getRole(int $roleId): string
    {
        return self::tryFrom($roleId)->name ?? self::USER->name;
    }
}
