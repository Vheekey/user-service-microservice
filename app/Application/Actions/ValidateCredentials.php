<?php

namespace App\Application\Actions;

use App\Domain\User\Entities\User;
use Illuminate\Support\Facades\Hash;

class ValidateCredentials
{
    public function handle(User $user, string $password): bool
    {
        return Hash::check($password, $user->getPassword());
    }
}
