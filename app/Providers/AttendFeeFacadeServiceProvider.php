<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\CoreService\Attend\AttendFeeFacade;

class AttendFeeFacadeServiceProvider extends ServiceProvider
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
            'AttendFeeFacade',
            AttendFeeFacade::class
        );
    }
}
