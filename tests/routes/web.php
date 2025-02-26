<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('bannable::welcome');
});


Route::namespace("\\Gecche\\Bannable\\Tests\\App\\Http\\Controllers")->group(function () {

    Route::get('/home', 'HomeController@index')->name('home');
});

Route::middleware(['web'])->group(function() {

    // Authentication Routes...
    Route::get('/login', '\Gecche\Bannable\Tests\App\Http\Controllers\Auth\LoginController@showLoginForm')->name('login');
    Route::post('/login', '\Gecche\Bannable\Tests\App\Http\Controllers\Auth\LoginController@login');
    Route::post('/logout', '\Gecche\Bannable\Tests\App\Http\Controllers\Auth\LoginController@logout')->name('logout');
});


