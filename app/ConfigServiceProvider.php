<?php
namespace Lhaa0\CacheDB;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

class ConfigServiceProvider extends LaravelServiceProvider
{
    /**
     * Boot the service provider.
     */
    public function boot()
    {
        $source = __DIR__. '/../config/cache-db.php';

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('cache-db.php')], 'cache-db-config');
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('cache-db');
        }

        $this->mergeConfigFrom($source, 'cache-db');
    }
}
