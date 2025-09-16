<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Tymon\JWTAuth\Providers\LaravelServiceProvider;

class JWTAuthServiceProvider extends LaravelServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();
    }

    /**
     * Boot the application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}