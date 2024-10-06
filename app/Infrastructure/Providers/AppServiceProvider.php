<?php

namespace App\Infrastructure\Providers;

use App\Domain\User\Interfaces\AuthenticationServiceInterface;
use App\Domain\User\Interfaces\UserRepositoryInterface;
use App\Infrastructure\Repositories\EloquentUserRepository;
use App\Infrastructure\Services\AuthenticationService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(AuthenticationServiceInterface::class, AuthenticationService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
