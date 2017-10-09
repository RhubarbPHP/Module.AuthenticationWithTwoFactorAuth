<?php

namespace Rhubarb\AuthenticationWithTwoFactorAuth\LoginProviders;

use Rhubarb\Scaffolds\Authentication\LoginProviders\LoginProvider;

class TwoFactorLoginProvider extends LoginProvider
{
    public function isLoggedIn()
    {
        // if on 2 factor page, then don't add a check for token was validated, otherwise, do

        //return false;
        return parent::isLoggedIn();
    }

    public function validateTwoFactorInput($token)
    {
        // set token validated true

    }

    public function hasValidToken()
    {
        // check session for valid token $this->data
    }

}