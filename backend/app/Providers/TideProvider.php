<?php

namespace App\Providers;

use App\Contracts\Integrations\DataServicesIntegrationContract;
use App\Integrations\GCTidesIntegration;
use App\Jobs\GetTides;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class TideProvider extends ServiceProvider
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
        $this->app->bind('App\Contracts\Integrations\DataServicesIntegrationContract', function ($app) {
            return new GCTidesIntegration(new Client());
        });
//        $this->app->when(GetTides::class)
//            ->needs(DataServicesIntegrationContract::class)
//            ->give(function () {
//                return new GCTidesIntegration(new Client());
//            });
    }
}
