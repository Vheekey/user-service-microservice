<?php

namespace App\Application\UseCases;


use App\Domain\User\Entities\User;
use App\Domain\User\Interfaces\AuthenticationServiceInterface;
use App\Domain\User\Interfaces\UserRepositoryInterface;
use Exception;

class GenerateAuthToken
{
    private AuthenticationServiceInterface $authenticationService;
    private UserRepositoryInterface $userRepository;

    public function __construct(
        AuthenticationServiceInterface $authenticationService,
        UserRepositoryInterface        $userRepository,
    )
    {
        $this->authenticationService = $authenticationService;
        $this->userRepository = $userRepository;
    }

    public function execute(User $user, ?array $abilities = []): string
    {
        return $this->authenticationService->generateToken($user, $abilities);
    }

    /**
     * @throws Exception
     */
    public function generateTokenViaEmail(string $email): string
    {
        $user = $this->userRepository->getUserByEmail($email);
        if (!$user) {
            abort(400, "User not found");
        }

        return $this->authenticationService->generateToken($user, $user->getUserAbilities($email));
    }
}
