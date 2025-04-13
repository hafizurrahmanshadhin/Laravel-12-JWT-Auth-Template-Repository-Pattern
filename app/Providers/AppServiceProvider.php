<?php

namespace App\Providers;

use App\Interfaces\Api\V1\Auth\ForgetPasswordRepositoryInterface;
use App\Interfaces\Api\V1\Auth\OTPRepositoryInterface;
use App\Interfaces\Api\V1\Auth\PasswordRepositoryInterface;
use App\Interfaces\Api\V1\Auth\UserRepositoryInterface;
use App\Repositories\Api\V1\Auth\ForgetPasswordRepository;
use App\Repositories\Api\V1\Auth\OTPRepository;
use App\Repositories\Api\V1\Auth\PasswordRepository;
use App\Repositories\Api\V1\Auth\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // auth
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ForgetPasswordRepositoryInterface::class, ForgetPasswordRepository::class);
        $this->app->bind(OTPRepositoryInterface::class, OTPRepository::class);
        $this->app->bind(PasswordRepositoryInterface::class, PasswordRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
