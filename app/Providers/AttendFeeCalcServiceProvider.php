<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\CoreService\Attend\AttendFeeCalcService;

class AttendFeeCalcServiceProvider extends ServiceProvider
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
            'AttendFeeCalc',
            AttendFeeCalcService::class
        );
    }
}
