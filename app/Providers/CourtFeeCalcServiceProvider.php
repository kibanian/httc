<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\CoreService\Court\CourtFeeCalcService;

class CourtFeeCalcServiceProvider extends ServiceProvider
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
            'CourtFeeCalc',
            CourtFeeCalcService::class
        );
    }
}
