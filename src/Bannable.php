<?php

namespace Gecche\UserBanning;

use Gecche\UserBanning\Events\Banned;
use Gecche\UserBanning\Events\Unbanned;

trait Bannable
{
    /**
     * Determine if the user has verified their email address.
     *
     * @return bool
     */
    public function isBanned()
    {
        return $this->banned;
    }

    /**
     * Mark the given user as banned.
     *
     * @return bool
     */
    public function ban()
    {
        $saved = $this->saveBannedStatus(1);
        if ($saved) {
            event(new Banned($this));
        }
        return $saved;
    }

    /**
     * Mark the given user as unbanned.
     *
     * @return bool
     */
    public function unBan()
    {
        $saved = $this->saveBannedStatus(0);
        if ($saved) {
            event(new Unbanned($this));
        }
        return $saved;
    }


    /**
     * Save the banned status.
     *
     * @return bool
     */
    protected function saveBannedStatus($bannedStatus) {
        return $this->forceFill([
            'banned' => $bannedStatus,
        ])->save();
    }

}
