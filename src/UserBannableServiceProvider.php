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

        Auth::provider('eloquent-bannable', function ($app, array $config) {
            // Return an instance of Illuminate\Contracts\Auth\UserProvider...

            return new EloquentBannableUserProvider($app['hash'], $config['model']);
        });

        Auth::provider('database-bannable', function ($app, array $config) {
            // Return an instance of Illuminate\Contracts\Auth\UserProvider...

            $connection = $app['db']->connection($config['connection'] ?? null);
            return new DatabaseBannableUserProvider($connection, $app['hash'], $config['table']);
        });
    }

}
