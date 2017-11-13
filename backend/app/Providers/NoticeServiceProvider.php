<?php

namespace App\Providers;

use App\Integrations\GCNoticeIntegration;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class NoticeServiceProvider extends ServiceProvider
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
        $this->app->bind('App\Contracts\Integrations\NotificationIntegrationContract', function ($app) {
            return new GCNoticeIntegration(new Client());
        });
    }
}
