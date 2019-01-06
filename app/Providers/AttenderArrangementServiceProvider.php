<?php

namespace App\Providers;

use App\CoreService\AttenderArrangementService;
use Illuminate\Support\ServiceProvider;

class AttenderArrangementServiceProvider extends ServiceProvider
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
            'AttenderArrangement',
            AttenderArrangementService::class
        );
    }
}
