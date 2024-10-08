<?php

namespace App\Application\UseCases;


use App\Domain\User\Entities\User;
use App\Domain\User\Interfaces\AuthenticationServiceInterface;

class GenerateAuthToken
{
    private AuthenticationServiceInterface $authenticationService;

    public function __construct(AuthenticationServiceInterface $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    public function execute(User $user, ?array $abilities = []): string
    {
        return $this->authenticationService->generateToken($user, $abilities);
    }
}
