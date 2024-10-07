<?php

namespace App\Application\UseCases;

use App\Application\Actions\SendUserTokenAction;
use App\Domain\User\Entities\User;
use App\Domain\User\Interfaces\TokenRepositoryInterface;
use Illuminate\Support\Facades\Log;

class SendUserToken
{
    private TokenRepositoryInterface $tokenRepository;
    private SendUserTokenAction $sendUserTokenAction;

    public function __construct(TokenRepositoryInterface $tokenRepository, SendUserTokenAction $sendUserTokenAction)
    {
        $this->tokenRepository = $tokenRepository;
        $this->sendUserTokenAction = $sendUserTokenAction;
    }

    public function execute(User $user): void
    {
        $token = $this->tokenRepository->createToken($user);
        $user->setToken($token);

        Log::info('Sending token notification to user', ['user_id' => $user->getId()]);

        $this->sendUserTokenAction->sendUserToken($user);
    }
}
