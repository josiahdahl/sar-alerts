<?php

namespace App\Providers;

use App\Jobs\GetWeatherAndWind;
use Illuminate\Support\ServiceProvider;
use App\Integrations\OpenWeatherMapIntegration;
use App\Integrations\Integration;

class WeatherAndWindServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when(GetWeatherAndWind::class)
            ->needs(Integration::class)
            ->give(function () {
                return new OpenWeatherMapIntegration();
            });
    }
}
