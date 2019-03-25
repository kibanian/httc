<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\CoreService\Tenniscourt\TenniscourtFeeCalcService;

class TenniscourtFeeCalcServiceProvider extends ServiceProvider
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
            'TenniscourtFeeCalc',
            TenniscourtFeeCalcService::class
        );
    }
}
