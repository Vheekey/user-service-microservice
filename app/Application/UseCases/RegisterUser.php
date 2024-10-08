<?php

namespace App\Application\UseCases;

use App\Domain\Events\NewUserRegistered;
use App\Domain\User\Entities\User;
use App\Domain\User\Interfaces\AuthenticationServiceInterface;
use App\Domain\User\Interfaces\UserRepositoryInterface;

class RegisterUser
{
    private UserRepositoryInterface $userRepository;
    private AuthenticationServiceInterface $authenticationService;

    public function __construct(
        UserRepositoryInterface        $userRepository,
        AuthenticationServiceInterface $authenticationService
    )
    {
        $this->userRepository = $userRepository;
        $this->authenticationService = $authenticationService;
    }

    public function execute(string $email, string $name, string $password): User
    {
        $userEntity = new User(
            $email,
            $name,
            $this->authenticationService->hashPassword($password)
        );
        $userEntity = $this->userRepository->save($userEntity);

        NewUserRegistered::dispatch($userEntity);

        return $userEntity;
    }
}
