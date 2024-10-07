<?php

namespace App\Application\UseCases;

use App\Domain\Notifications\SendToken;
use App\Domain\User\Entities\User;
use App\Domain\User\Interfaces\TokenRepositoryInterface;
use Illuminate\Support\Facades\Notification;

class SendUserToken
{
    private TokenRepositoryInterface $tokenRepository;

    public function __construct(TokenRepositoryInterface $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }

    public function execute(User $user): void
    {
        $token = $this->tokenRepository->createToken($user);

        Notification::route('mail', [
            $user->getEmail() => $user->getName()
        ])->notify(new SendToken($user, $token));
    }
}
