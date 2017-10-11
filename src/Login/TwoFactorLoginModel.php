<?php

namespace Rhubarb\AuthenticationWithTwoFactorAuth\Login;

use Rhubarb\Crown\Events\Event;
use Rhubarb\Scaffolds\Authentication\Leaves\LoginModel;

class TwoFactorLoginModel extends LoginModel
{
    /** @var Event */
    public $verifyCodeEvent;
    public $Code;
    public $loginProvider;
    public $promptForCode;
}