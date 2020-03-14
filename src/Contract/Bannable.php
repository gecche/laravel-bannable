<?php

namespace Gecche\UserBannable\Contract;

use Gecche\UserBannable\Banned;
use Gecche\UserBannable\Unbanned;
use Illuminate\Contracts\Auth\Authenticatable;

interface Bannable extends Authenticatable
{
    /**
     * Get the column name for the "banned" value.
     *
     * @return string
     */
    public function getBannedName();

    /**
     * Determine if the user is banned.
     *
     * @return bool
     */
    public function isBanned();


}
