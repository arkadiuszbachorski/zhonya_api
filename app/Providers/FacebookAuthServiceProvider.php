<?php

namespace App\Providers;

use App\Services\FacebookAuth;
use Illuminate\Support\ServiceProvider;

class FacebookAuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Services\FacebookAuth', function() {
            return new FacebookAuth();
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
