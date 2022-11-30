<?php

namespace Lhaa0\CacheDB;

use Illuminate\Support\Facades\Redis;

class CacheRedis {

    private $instance;
    private $key;
    private $time;

    public function __construct($instance, $key, $time = null)
    {
        $this->instance = $instance;
        $this->key = $key;
        $this->time = $time;
    }

    public function __call($name, $argument) {
        if (!$this->hasKey()) {
            $data = $this->instance->{$name}(...$argument);
            $setData = $data;
            if (get_class($data) == 'Illuminate\Database\Query\Builder')
                return $data;

            if (is_object($data)) {
                $setData = json_encode($setData);
            }
            $this->setCache($setData);
        } else {
            $data = $this->getCache();
        }
        return $data;
    }

    function setCache($data)
    {
        Redis::set($this->key, $data, 'PX', ($this->time ?? config('cache-db.time', 1000 * 60)));
    }

    function getCache()
    {
        $data = Redis::get($this->key);
        if ($this->isJson($data)) {
            $data = collect(json_decode($data));
        }
        return $data;
    }
    public function hasKey()
    {
        return Redis::exists($this->key);
    }

    function isJson($string) {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
