<?php

namespace Gecche\Bannable;

use Illuminate\Auth\DatabaseUserProvider;
use Gecche\Bannable\Contracts\Bannable;

class DatabaseBannableUserProvider extends DatabaseUserProvider
{

    /**
     * Get the generic user.
     *
     * @param  mixed  $user
     * @return GenericBannableUser|null
     */
    protected function getGenericUser($user)
    {
        if (! is_null($user)) {
            $genericUser = new GenericBannableUser((array) $user);
            return $genericUser->isBanned() ? null : $genericUser;
        }
    }


}
