<?php

namespace App\Domain\User\Interfaces;

use App\Domain\User\Entities\User;

interface UserRepositoryInterface
{
    public function save(User $user): User;
}
