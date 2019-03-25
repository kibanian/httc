<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\CoreService\Card\CardRegistService;

class CardRegistServiceProvider extends ServiceProvider
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
            'CardRegist',
            CardRegistService::class
        );
    }
}
