<?php

namespace App\Providers;

use App\Services\DefaultSessionService;
use App\Services\RedisSessionService;
use App\Services\SessionService;
use Illuminate\Support\ServiceProvider;

class SessionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SessionService::class, function ($app) {
            $driver = config('session.driver');
            
            if ($driver === 'redis') {
                return new RedisSessionService();
            }

            return new DefaultSessionService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
