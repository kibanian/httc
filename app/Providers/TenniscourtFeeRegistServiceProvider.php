<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\CoreService\Tenniscourt\TenniscourtFeeRegistService;

class TenniscourtFeeRegistServiceProvider extends ServiceProvider
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
            'TenniscourtFeeRegist',
            TenniscourtFeeRegistService::class
        );
    }
}
