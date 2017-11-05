<?php

namespace App\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use App\Integrations\OpenWeatherMapIntegration;

class WeatherServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Contracts\Integrations\WeatherIntegrationContract', function ($app) {
            return new OpenWeatherMapIntegration(new Client());
        });
    }
}
