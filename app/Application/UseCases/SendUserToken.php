<?php

namespace App\Application\UseCases;

use App\Domain\User\Entities\User;
use App\Domain\User\Interfaces\TokenRepositoryInterface;
use App\Infrastructure\Kafka\KafkaProducer;
use Illuminate\Support\Facades\Log;

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
        $user->setToken($token);

        Log::info('Emitting token notification to Kafka');

        KafkaProducer::setTopic(config('kafka.topics.notifications'))
            ->send([$user]);
    }
}
