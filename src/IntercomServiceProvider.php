<?php

namespace NotificationChannels\Intercom;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use Intercom\IntercomClient;

class IntercomServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        if (! $this->app->has(IntercomClient::class)) {
            $this->app->when(IntercomChannel::class)
                ->needs(IntercomClient::class)
                ->give(static function () {
                    /* @var Config $config */
                    return new IntercomClient(
                        Config::get('services.intercom.token', ''),
                        null
                    );
                });
        }
    }

    /**
     * Register any package services.
     */
    public function register(): void
    {
        Notification::extend('intercom', static function (Container $app) {
            return $app->make(IntercomChannel::class);
        });
    }
}
