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

trait BannableTestCaseTrait
{


    /*
     * Test Login a banned and a unbanned user via loginUsingId method
    */
    public function testLoginUsingId()
    {

        $user = $this->getTestUser();

        $this->ban($user);

        Auth::loginUsingId(1);

        $this->assertEquals(Auth::id(), null);

        $this->unban($user);
        //
        Auth::loginUsingId(1);

        $this->assertEquals(Auth::id(), 1);

    }

    /*
     * Test Login an unbanned user via http
    */
    public function testLoginHttpUnbanned()
    {
        $user = $this->getTestUser();

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
        $user = $this->getTestUser();

        $this->assertEquals(Auth::id(), null);

        $this->visit('/')
            ->see(env("APP_NAME"));

        $this->ban($user);

        $this->visit('/login')
            ->type($user->email,'email')
            ->type('password','password')
            ->press('Login')
            ->seePageIs('/login');
    }


    /*
     * Test API access (token) for an unbanned user
    */
    public function testAPIAccessUnbanned()
    {
        $user = $this->getTestUser();

        $this->visit('/api/user?api_token='.$user->api_token)
            ->see($user->email);

    }


    /*
     * Test API access (token) for an unbanned user
    */
    public function testAPIAccessBanned()
    {
        $user = $this->getTestUser();

        $this->ban($user);

        $this->visit('/api/user?api_token='.$user->api_token)
            ->dontSee($user->name)
            ->seePageIs('/login');

    }

    public function testBasicAuthUnbanned() {
        $user = $this->getTestUser();
        //$this->ban($user);
        $response = $this->call('GET', '/api/userbasic', [], [], [],
            ['PHP_AUTH_USER' => $user->email, 'PHP_AUTH_PW' => 'password']);




        $this->assertResponseStatus( 200 );
        $this->assertStringContainsString($response->getContent(),$user->name);


    }

    public function testBasicAuthBanned() {
        $user = $this->getTestUser();
        $this->ban($user);
        $this->call('GET', '/api/userbasic', [], [], [],
            ['PHP_AUTH_USER' => $user->email, 'PHP_AUTH_PW' => 'password']);
        
        $this->assertResponseStatus( 401 );


    }



}
