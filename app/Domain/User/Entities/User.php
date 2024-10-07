<?php

namespace App\Domain\User\Entities;

use App\Shared\Enums\RoleEnum;

class User
{
    private int $id;
    private string $email;
    private string $name;
    private string $password;
    private int $roleId;
    private bool $is_active;

    public function __construct(
        string $email,
        string $name,
        string $password,
        int    $roleId = RoleEnum::USER->value
    )
    {
        $this->email = $email;
        $this->name = $name;
        $this->password = $password;
        $this->roleId = $roleId;
        $this->is_active = true;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }


    public function changeEmail(string $newEmail): void
    {
        $this->email = $newEmail;
    }

    public function changePassword(string $newPassword): void
    {
        $this->password = $newPassword;
    }

    public function getRole(): string
    {
        return RoleEnum::getRole($this->roleId);
    }

    public function setRoleId(int $roleId): void
    {
        $this->roleId = $roleId;
    }

    public function getRoleId(): int
    {
        return $this->roleId;
    }

    public function getActive(): bool
    {
        return $this->is_active;
    }

    public function switchActive(): void
    {
        $this->is_active = !$this->is_active;
    }

    public function toUserArray(): array
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
            'password' => $this->password,
            'role_id' => $this->getRoleId(),
        ];
    }

    public function getUserArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'password' => $this->password,
            'role_id' => $this->getRoleId(),
            'role' => $this->getRole(),
            'is_active' => $this->getActive()
        ];
    }
}
