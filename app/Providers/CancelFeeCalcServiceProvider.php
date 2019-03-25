<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\CoreService\Cancel\CancelFeeCalcService;

class CancelFeeCalcServiceProvider extends ServiceProvider
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
            'CancelFeeCalc',
            CancelFeeCalcService::class
        );

    }
}
