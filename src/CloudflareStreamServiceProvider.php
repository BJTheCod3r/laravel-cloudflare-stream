<?php

namespace Bjthecod3r\CloudflareStream;

use Illuminate\Support\ServiceProvider;

class CloudflareStreamServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('cloudflare-stream', function () {
            return new CloudflareStream();
        });
    }


    /**
     * Bootstrap package services
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/cloudflare-stream.php' => config_path('cloudflare-stream.php')
        ]);
    }
}
