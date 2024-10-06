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
}
