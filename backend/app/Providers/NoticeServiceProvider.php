<?php

namespace App\Providers;

use App\Integrations\DataNoticeIntegration;
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
        $this->app->bind('App\Contracts\Integrations\NotificationIntegrationProvider', function ($app) {
            return new DataNoticeIntegration();
        });
    }
}
