<?php

namespace Gecche\UserBannable;

use Gecche\UserBannable\Contract\Bannable as UserContract;
use Illuminate\Auth\GenericUser;

class GenericBannableUser extends GenericUser implements UserContract
{
    /**
     * Get the column name for the "banned" value.
     *
     * @return string
     */
    public function getBannedName() {
        return "banned";
    }

    /**
     * Determine if the user is banned.
     *
     * @return bool
     */
    public function isBanned()
    {
        return $this->attributes[$this->getBannedName()];
    }

}
