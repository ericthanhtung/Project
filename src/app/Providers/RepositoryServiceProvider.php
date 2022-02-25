<?php

namespace App\Providers;

use App\Services\Authentication\AuthenticationService;
use App\Services\Authentication\AuthenticationServiceInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(AuthenticationServiceInterface::class, AuthenticationService::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
