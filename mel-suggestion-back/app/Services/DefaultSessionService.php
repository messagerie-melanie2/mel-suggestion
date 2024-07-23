<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

class DefaultSessionService extends SessionService
{
    public function connect() {}
    
    public function get($key)
    {
        return Session::get($key);
    }

    public function set($key, $value)
    {
        Session::put($key, $value);
    }

    public function has($key)
    {
        return Session::has($key);
    }

    public function forget($key) 
    {
        Session::forget($key);
    }

    public function flush() 
    {
        Session::flush();
    }
}