<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\CoreService\Event\EventRegistService;

class EventRegistServiceProvider extends ServiceProvider
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
            'EventRegist',
            EventRegistService::class
        );
    }
}
