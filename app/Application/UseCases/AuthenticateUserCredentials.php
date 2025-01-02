<?php

namespace App\Application\UseCases;

use App\Application\Actions\ValidateCredentials;
use App\Domain\User\Entities\User;
use App\Domain\User\Interfaces\UserRepositoryInterface;

class AuthenticateUserCredentials
{
    private UserRepositoryInterface $userRepository;
    private ValidateCredentials $validateCredentials;

    public function __construct(
        UserRepositoryInterface $userRepository,
        ValidateCredentials     $validateCredentials
    )
    {
        $this->userRepository = $userRepository;
        $this->validateCredentials = $validateCredentials;
    }

    public function execute(string $email, string $password): ?User
    {
        $user = $this->userRepository->getUserByEmail($email);

        if (!$user || !$this->validateCredentials->handle($user, $password)) {
            return null;
        }

        return $user;
    }
}
