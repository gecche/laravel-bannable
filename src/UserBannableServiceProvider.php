<?php

namespace Gecche\UserBannable;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class UserBannableServiceProvider extends ServiceProvider
{


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'user-banning');


        Auth::extend('jwt', function ($app, $name, array $config) {
            // Return an instance of Illuminate\Contracts\Auth\Guard...

            return new JwtGuard(Auth::createUserProvider($config['provider']));
        });

    }

}
