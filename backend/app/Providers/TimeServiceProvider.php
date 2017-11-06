<?php

namespace App\Providers;

use App\Integrations\LocationTimeIntegration;
use Illuminate\Support\ServiceProvider;

class TimeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Contracts\Integrations\TimeIntegrationContract', function () {
            return new LocationTimeIntegration();
        });
    }
}
