<?php

namespace App\Infrastructure\Providers;

use App\Application\Listeners\TokenNotificationSubscriber;
use App\Domain\Persistence\Models\PersonalAccessToken;
use App\Domain\User\Interfaces\AuthenticationServiceInterface;
use App\Domain\User\Interfaces\TokenRepositoryInterface;
use App\Domain\User\Interfaces\UserRepositoryInterface;
use App\Infrastructure\Repositories\CacheTokenRepository;
use App\Infrastructure\Repositories\EloquentUserRepository;
use App\Infrastructure\Services\AuthenticationService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(AuthenticationServiceInterface::class, AuthenticationService::class);
        $this->app->bind(TokenRepositoryInterface::class, CacheTokenRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        Event::subscribe(TokenNotificationSubscriber::class);
    }
}
