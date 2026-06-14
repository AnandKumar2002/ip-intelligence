<?php

namespace Parvion\IpIntelligence;

use Illuminate\Support\ServiceProvider;

class IpIntelligenceServiceProvider extends ServiceProvider
{
    /**
        * Register any application services.
        */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/ip-intelligence.php', 'ip-intelligence'
        );

        $this->app->singleton('ip-intelligence', function ($app) {
            return new IpIntelligence($app['request']);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/ip-intelligence.php' => config_path('ip-intelligence.php'),
            ], 'ip-intelligence-config');
        }
    }
}
