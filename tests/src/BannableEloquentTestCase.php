<?php
/**
 * Created by PhpStorm.
 * User: gecche
 * Date: 01/10/2019
 * Time: 11:15
 */

namespace Gecche\Bannable\Tests;

use Gecche\Bannable\Tests\Models\User;
use Gecche\Bannable\BannableServiceProvider as ServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BannableEloquentTestCase extends \Orchestra\Testbench\BrowserKit\TestCase
{

    use RefreshDatabase;

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


        factory(User::class, 2)->create();

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
     * The code policy in these tests simply allows for:
     * - all the codes to user 1
     * - all the codes with code starting with "00" to user 2
     * - all the codes with a null description to user 3
     * - only code with id 1 to all other users
     * - no codes for guests
     *
     * Furthermore in \Gecche\Bannable\Tests\AuthServiceProvider, a before callback is registeres to grant access to
     * user 5, so usign the all method.
     *
     */


    /*
     * In the first 3 tests, we check the Bannable@beforeAcl method when it returns a Builder.
     * See the logic in \Gecche\Bannable\Tests\AuthServiceProvider
     *
     */


    /*
     * Test bannable User methods ban and Unban
     */
    public function testBanAndUnBanEloquentUser()
    {
        $dbUser = DB::table('users')->find(1);

        //By default the banned value is 0 once seeded.
        $this->assertEquals($dbUser->banned, 0);

        $user = User::find(1);

        $user->ban();
        //
        $dbUser = DB::table('users')->find(1);
        //By default the banned value is 0 once seeded.
        $this->assertEquals($dbUser->banned, 1);

        $user->unBan();
        //
        $dbUser = DB::table('users')->find(1);
        //By default the banned value is 0 once seeded.
        $this->assertEquals($dbUser->banned, 0);

    }

    /*
     * Test Login a banned and a unbanned user via loginUsingId method
    */
    public function testLoginUsingId()
    {
        $this->assertEquals(Auth::id(), null);

        Auth::loginUsingId(1);

        $this->assertEquals(Auth::id(), 1);

        Auth::logout();

        $this->assertEquals(Auth::id(), null);

        $user = User::find(1);

        $user->ban();

        Auth::loginUsingId(1);

        $this->assertEquals(Auth::id(), null);

        $user->unBan();
        //
        Auth::loginUsingId(1);

        $this->assertEquals(Auth::id(), 1);

    }

    /*
     * Test Login an unbanned user via http
    */
    public function testLoginHttpUnbanned()
    {
        $user = User::find(1);

        $this->assertEquals(Auth::id(), null);

        $this->visit('/')
            ->see(env("APP_NAME"));

        $this->visit('/login')
            ->type($user->email,'email')
            ->type('password','password')
            ->press('Login')
            ->seePageIs('/home');
    }

    /*
     * Test Login a banned user via http
    */
    public function testLoginHttpBanned()
    {
        $user = User::find(1);

        $this->assertEquals(Auth::id(), null);

        $this->visit('/')
            ->see(env("APP_NAME"));

        $user->ban();

        $this->visit('/login')
            ->type($user->email,'email')
            ->type('password','password')
            ->press('Login')
            ->seePageIs('/login');
    }


}
