<?php

namespace NotificationChannels\SynologyChat;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class SynologyChatServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Bootstrap code here.

        $this->app->when(SynologyChatChannel::class)
            ->needs(SynologyChat::class)
            ->give(static function () {
                return new SynologyChat(
                    new HttpClient()
                );
            });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        Notification::extend('synologyChat', static function (Container $app) {
            return $app->make(SynologyChatChannel::class);
        });
    }
}
