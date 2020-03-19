[![Laravel](https://img.shields.io/badge/Laravel-5.x-orange.svg?style=flat-square)](http://laravel.com)
[![Laravel](https://img.shields.io/badge/Laravel-6.x-orange.svg?style=flat-square)](http://laravel.com)
[![Laravel](https://img.shields.io/badge/Laravel-7.x-orange.svg?style=flat-square)](http://laravel.com)
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)

# laravel-bannable
Laravel package to handle bannable users

## Description
  In many apps, your users could be banned from the platform due to various causes, i.e.: for not respecting platform's 
  rules or for not respecting other users or simply for not paying the monthly fees :)
  
  This package adds a `banned` attribute to the standard users in order to prevent the authentication of such 
  banned users. Fully tested.

## Documentation

### Version Compatibility

 Laravel  | Bannable
:---------|:----------
 5.5.x    | 1.1.x
 5.6.x    | 1.2.x
 5.7.x    | 1.3.x
 5.8.x    | 1.4.x
 6.x      | 2.x
 7.x      | 3.x

### Installation

Add gecche/laravel-bannable as a requirement to composer.json:

```javascript
{
    "require": {
        "gecche/laravel-bannable": "2.*"
    }
}
```

This package makes use of the discovery feature.

### Basic usage

To use the package's features, first you have to run the provided migration which simply adds a `banned` boolean field to the
 users' table.
 
Then, if you use Eloquent users you have to add the bannable features to the User model class:

```php
<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Gecche\Bannable\Bannable;
use Gecche\Bannable\Contracts\Bannable as BannableContract;

class User extends Authenticatable implements BannableContract
{
    use Notifiable;
    use Bannable;

...
```

Finally, you have to change the `auth.php` configuration, changing the user provider's driver to `eloquent-bannable`

```php
'providers' => [
        'users' => [
            'driver' => 'eloquent-bannable',
            'model' => App\User::class,
        ],
    ],
```

That's it! From now on, if an user has the banned attribute set to true, it will not be able to authenticate to the 
platform.

The package provides a `database-bannable` user provider's driver too.

#### Change the name of the "banned" attribute

To change the name of the "banned" attribute, simply override in you Eloquent User class the `getBannedName` method, 
replacing the `banned` name with whatever name you want:

```php
    /**
     * Get the column name for the "banned" value.
     *
     * @return string
     */
    public function getBannedName()
    {
        return 'banned';
    }
```

Remember to change the name of the database field too!

#### Ban and unban users

The `Gecche\Bannable\Bannable` trait also provides two simple methods, namely 
`ban` and `unban` which obviously ban and unban the user. Both methods also fire 
a correspondent event, namely `Gecche\Bannable\Events\Banned` and `Gecche\Bannable\Events\Unbanned` events.









