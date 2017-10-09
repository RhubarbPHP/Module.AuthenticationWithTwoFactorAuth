<?php

namespace Rhubarb\AuthenticationWithTwoFactorAuth\Login;

use Rhubarb\Crown\Exceptions\ForceResponseException;
use Rhubarb\Crown\Response\RedirectResponse;
use Rhubarb\Scaffolds\Authentication\Leaves\Login;

class TwoFactorLogin extends Login
{
    protected function onSuccess()
    {
        throw new ForceResponseException(new RedirectResponse('twoFactorAuth/'));
    }
}