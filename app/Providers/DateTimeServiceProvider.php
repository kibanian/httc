<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\CoreService\DateTimeService;

class DateTimeServiceProvider extends ServiceProvider
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
            'DateTime',
            DateTimeService::class
        );
    }
}
