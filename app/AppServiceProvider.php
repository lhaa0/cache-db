<?php

namespace Lhaa0\CacheDB;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    function register()
    {
    }

    function boot() {
        Builder::macro('cacheThis',
        function ($key, $time = null)
        {
            return (new CacheRedis($this, $key, $time));
        });
    }
}
