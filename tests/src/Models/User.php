<?php namespace Gecche\Bannable\Tests\Models;

use Gecche\Bannable\Bannable;
use Gecche\Bannable\Contracts\Bannable as BannableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Notifications\Notifiable;


class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    BannableContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    use Bannable;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

}

