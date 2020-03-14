<?php

namespace Gecche\UserBanning\Contract;

use Gecche\UserBanning\Banned;
use Gecche\UserBanning\Unbanned;

interface Bannable
{
    /**
     * Determine if the user has verified their email address.
     *
     * @return bool
     */
    public function isBanned();

    /**
     * Mark the given user as banned.
     *
     * @return bool
     */
    public function ban();

    /**
     * Mark the given user as unbanned.
     *
     * @return bool
     */
    public function unBan();

}
