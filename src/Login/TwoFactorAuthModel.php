<?php

namespace Rhubarb\AuthenticationWithTwoFactorAuth\Login;

use Rhubarb\Crown\Events\Event;
use Rhubarb\Leaf\Leaves\LeafModel;

class TwoFactorAuthModel extends LeafModel
{
    /** @var Event */
    public $twoFactorConfirmEvent;
    public $verificationCode;

    public function __construct()
    {
        parent::__construct();

        $this->twoFactorConfirmEvent = new Event();
    }
}