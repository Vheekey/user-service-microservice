<?php

namespace App\Application\Listeners;

use App\Application\UseCases\SendUserToken;
use App\Domain\Events\NewUserRegistered;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Log;

class TokenNotificationSubscriber
{
    private SendUserToken $sendUserToken;

    public function __construct(SendUserToken $sendUserToken)
    {
        $this->sendUserToken = $sendUserToken;
    }

    /**
     * Handle new user registered event.
     */
    public function handleUserRegistered(NewUserRegistered $newUserRegistered): void
    {
        Log::info('New user registered: ', [$newUserRegistered]);
        $this->sendUserToken->execute($newUserRegistered->user);
    }


    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            NewUserRegistered::class => 'handleUserRegistered'
        ];
    }
}
