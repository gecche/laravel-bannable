<?php
/**
 * Created by PhpStorm.
 * User: gecche
 * Date: 01/10/2019
 * Time: 11:15
 */

namespace Gecche\Bannable\Tests;

use Gecche\Bannable\Tests\App\Models\User;
use Gecche\Bannable\BannableServiceProvider as ServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Gecche\Bannable\Tests\App\TestServiceProvider;

class BannableDatabaseTestCase extends \Orchestra\Testbench\BrowserKit\TestCase
{

    use RefreshDatabase;
    use BannableTestCaseTrait;


    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->withFactories(
            __DIR__ . '/../database/factories'
        );
//        app()->bind(AuthServiceProvider::class, function($app) { // not a service provider but the target of service provider
//            return new \Gecche\Bannable\Tests\AuthServiceProvider($app);
//        });

        $this->artisan('migrate', ['--database' => 'testbench']);

        $this->beforeApplicationDestroyed(function () {
            $this->artisan('migrate:rollback');
        });


        factory(User::class, 1)->create();

    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // set up database configuration
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('auth.providers', [
            'users' => [
                'driver' => 'database-bannable',
                'table' => 'users',
            ]
        ]);
        $app['config']->set('auth.guards', [
            'web' => [
                'driver' => 'session',
                'provider' => 'users',
            ],
            'api' => [
                'driver' => 'token',
                'provider' => 'users',
                'hash' => false,
            ],
        ]);
    }

    /**
     * Get Sluggable package providers.
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
            TestServiceProvider::class,
        ];
    }


    protected function getTestUser() {

        Auth::loginUsingId(1);
        $user = Auth::user();
        Auth::logout();
        return $user;

    }

    protected function ban($user) {
        return $this->updateBannedName($user,1);
    }

    protected function unban($user) {
        return $this->updateBannedName($user,0);
    }


    protected function updateBannedName($user,$value) {
        DB::table('users')
            ->where('email',$user->email)
            ->update([$user->getBannedName() => $value]);
    }
}
