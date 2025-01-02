<?php

namespace App\Infrastructure\Repositories;

use AllowDynamicProperties;
use App\Domain\Persistence\Models\UserModel;
use App\Domain\User\Entities\User;
use App\Domain\User\Interfaces\UserRepositoryInterface;

#[AllowDynamicProperties] class EloquentUserRepository implements UserRepositoryInterface
{
    private UserModel $eloquentUser;

    public function __construct(UserModel $eloquentUser)
    {
        $this->eloquentUser = $eloquentUser;
    }

    public function save(User $user): User
    {
        $this->eloquentUser->fill($user->toUserArray());
        $this->eloquentUser->save();

        $user->setId($this->eloquentUser->id);

        return $user;
    }


    public function getUserByEmail(string $email): ?User
    {
        $eloquentUser = $this->eloquentUser->where('email', $email)->first();

        if (!$eloquentUser) {
            return null;
        }

        $user = new User(
            $eloquentUser->email,
            $eloquentUser->name,
            $eloquentUser->password,
            $eloquentUser->role_id,
        );

        $user->setId($eloquentUser->id);

        return $user;
    }
}
