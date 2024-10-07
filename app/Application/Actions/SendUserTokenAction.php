<?php

namespace App\Application\Actions;

use App\Domain\User\Entities\User;
use App\Infrastructure\Kafka\KafkaProducer;
use App\Infrastructure\Notifications\SendToken;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendUserTokenAction
{
    public function sendUserToken(User $user): void
    {
        app(Pipeline::class)
            ->send($user)
            ->through([
                // Step 1: Try to send via Kafka
                function ($user, $next) {
                    if ($this->sendViaKafka($user)) {
                        $user->markAsNotified();
                        return $next($user);
                    }
                    Log::error('Token notification failed to send via Kafka.');
                    return $next($user);
                },
                // Step 2: Fallback to Laravel Notification
                function ($user, $next) {
                    if (!$user->hasBeenNotified()) {
                        $this->sendViaNotificationFacade($user);
                    }

                    return $next($user);
                },
            ])
            ->then(function ($user) {
                Log::info('Token notification pipeline completed successfully.');
            });
    }

    private function sendViaKafka(User $user): bool
    {
        Log::info('Sending token notification via Kafka.');
        return KafkaProducer::setTopic(config('kafka.topics.notifications'))
            ->send([$user]);
    }

    private function sendViaNotificationFacade(User $user): void
    {
        Log::info('Sending token notification via notification facade.');
        Notification::route('mail', [$user->getEmail() => $user->getName()])
            ->notify(new SendToken($user));

        $user->markAsNotified();
    }
}
