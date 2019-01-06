<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\CoreService\CancelRegistService;

class CancelRegistServiceProvider extends ServiceProvider
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
            'CancelRegist',
            CancelRegistService::class
        );
    }
}