<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\CoreService\Card\CardTypeRegistService;

class CardTypeRegistServiceProvider extends ServiceProvider
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
            'CardTypeRegist',
            CardTypeRegistService::class
        );
    }
}
