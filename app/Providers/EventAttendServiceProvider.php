<?php

namespace App\Providers;

use App\CoreService\Event\EventAttendService;
use Illuminate\Support\ServiceProvider;

class EventAttendServiceProvider extends ServiceProvider
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
            'EventAttend',
            EventAttendService::class
        );
    }
}
