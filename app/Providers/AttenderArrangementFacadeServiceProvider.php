<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\CoreService\Attend\AttenderArrangementFacade;

class AttenderArrangementFacadeServiceProvider extends ServiceProvider
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
            'AttenderArrangementFacade',
            AttenderArrangementFacade::class
        );
    }
}
