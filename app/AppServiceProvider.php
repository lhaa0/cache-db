<?php

namespace Lhaa0\CacheDB;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    function boot() {
        Builder::macro('cacheThis', function ($key, $px = null)
        {
            $db = $this;
            $exists = Redis::exists($key);
            if (!$exists) {
                $data = $db->get();
                Redis::set($key, json_encode($data), 'PX', ($px ?? config('cache-db.time', 1000 * 60)) );
                return $data;
            } else {
                return collect(json_decode(Redis::get($key)));
            }
        });
    }
}
