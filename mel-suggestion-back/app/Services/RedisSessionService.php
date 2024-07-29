<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class RedisSessionService extends SessionService
{
    public function connect() {}
    
    public function get($key)
    {
        return json_decode(Redis::get($key));
    }

    public function set($key, $value)
    {
        Redis::set($key, json_encode($value));
    }

    public function has($key)
    {
        return Redis::exists($key);
    }

    public function forget($key) 
    {
        Redis::del($key);
    }
}