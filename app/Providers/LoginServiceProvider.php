<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\CoreService\Auth\LoginService;

class LoginServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(
            'Login',
            LoginService::class
        );
    }
}
