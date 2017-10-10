<?php

namespace Rhubarb\AuthenticationWithTwoFactorAuth\LoginProviders;

use Aws\Sms\SmsClient;
use OTPHP\TOTP;
use Rhubarb\AuthenticationWithTwoFactorAuth\User;
use Rhubarb\AwsSnsSmsProvider\SMSProviders\AwsSnsSmsProvider;
use Rhubarb\Scaffolds\Authentication\LoginProviders\LoginProvider;

class TwoFactorLoginProvider extends LoginProvider
{
    /** @var User */
    private $user;

    /** @var TOTP */
    private $totp;

    public function isLoggedIn()
    {
        if (parent::isLoggedIn()) {
            $this->user = $this->getLoggedInUser();
        }

        // if on 2 factor page, then don't add a check for token was validated, otherwise, do

        return parent::isLoggedIn();
    }

    public function validateTwoFactorInput($input)
    {
        // set token validated true
        if ($this->totp->verify($input)) {
            $this->loggedIn = true;
            $this->loggedInUserIdentifier = $this->user->getUniqueIdentifier();
        } else {
            $this->loggedIn = false;
        }
        $this->storeSession();
    }

    public function hasValidToken()
    {
        // check session for valid token $this->data
    }

    public function sendTotpCode()
    {
        $this->totp = TOTP::create($this->user->TFASecret, 30, 'sha256');
        $sms = new AwsSnsSmsProvider();
        $sms->send();
    }
}