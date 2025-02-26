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

class BannableEloquentTestCase extends \Orchestra\Testbench\BrowserKit\TestCase
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
                'driver' => 'eloquent-bannable',
                'model' => User::class,
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


    /*
     * Test bannable User methods ban and Unban
     */
    public function testBanAndUnbanEloquentUser()
    {
        $dbUser = DB::table('users')->find(1);

        //By default the banned value is 0 once seeded.
        $this->assertEquals($dbUser->banned, 0);

        $user = $this->getTestUser();

        $user->ban();
        //
        $dbUser = DB::table('users')->find(1);
        //By default the banned value is 0 once seeded.
        $this->assertEquals($dbUser->banned, 1);

        $user->unban();
        //
        $dbUser = DB::table('users')->find(1);
        //By default the banned value is 0 once seeded.
        $this->assertEquals($dbUser->banned, 0);

    }

    
    
    protected function getTestUser() {
        return User::find(1);
    }


    protected function ban($user) {
        $user->ban();
    }

    protected function unban($user) {
        $user->unban();
    }

}
