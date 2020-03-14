<?php

namespace Gecche\Bannable\Contract;

use Gecche\Bannable\Banned;
use Gecche\Bannable\Unbanned;
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
