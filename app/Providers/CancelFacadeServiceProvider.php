<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\CoreService\CancelFacade;

class CancelFacadeServiceProvider extends ServiceProvider
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
            'CancelFacade',
            CancelFacade::class
        );
    }
}
