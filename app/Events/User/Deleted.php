<?php

namespace Vanguard\Events\User;

use Vanguard\User;

class Deleted
{
    /**
     * @var User
     */
    protected $deletedUserr;

    public function __construct(User $deletedUser)
    {
        $this->deletedUser = $deletedUser;
    }

    /**
     * @return User
     */
    public function getDeletedUser()
    {
        return $this->deletedUser;
    }
}
