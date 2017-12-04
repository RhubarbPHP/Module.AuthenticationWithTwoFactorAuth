<?php

namespace Rhubarb\AuthenticationWithTwoFactorAuth\LoginProviders;

use OTPHP\TOTP;
use Rhubarb\Scaffolds\Authentication\LoginProviders\LoginProvider;
use Rhubarb\Sms\Sendables\Sms\SimpleSms;

class TwoFactorLoginProvider extends LoginProvider
{
    private static $skip2Factor;
    /**
     * @var
     * @deprecated remove once we're sending the codes!
     */
    public $verificationCode;
    public $timestamp;
    public $twoFactorVerified = false;
    public $codeSent = false;
    public $codeAttempted = false;

    public function __construct(
        $modelClassName = "User",
        $usernameColumnName = "Email",
        $passwordColumnName = "Password",
        $activeColumnName = "Enabled"
    ) {
        parent::__construct($modelClassName, $usernameColumnName, $passwordColumnName, $activeColumnName);
    }

    public function isTwoFactorVerified(): bool
    {
        return $this->twoFactorVerified;
    }

    public function hasProvidedCorrectUserCredentials(): bool
    {
        return parent::isLoggedIn();
    }

    public function isLoggedIn()
    {
        return (self::$skip2Factor || $this->isTwoFactorVerified()) && $this->hasProvidedCorrectUserCredentials();
    }

    public function validateCode($code)
    {
        if ($this->createTOTPHelper()->verify($code, $this->timestamp)) {
            $this->twoFactorVerified = true;
        } else {
            $this->codeAttempted = true;
        }

        $this->storeSession();
    }

    protected function onLogOut()
    {
        $this->twoFactorVerified = false;
        $this->codeSent = false;
        $this->codeAttempted = false;
        $this->storeSession();
        parent::onLogOut();
    }

    protected function createTOTPHelper(): TOTP
    {
        return TOTP::create($this->getLoggedInUser()->TFASecret, 30, 'sha256');
    }

    public function createAndSendCode()
    {
        $this->timestamp = time();
        $code = $this->createTOTPHelper()->at($this->timestamp);
        $this->verificationCode = $code;

        $this->sendCode($code);
        $this->codeSent = true;
        $this->storeSession();
    }

    protected function sendCode(string $code)
    {
        $sms = new SimpleSms();
        $sms->setText('Your verification code is: ' . $code);
        $sms->addRecipientByNumber($this->getLoggedInUser()->MobileNumber);
        //SendableProvider::selectProviderAndSend($sms);
    }

    public static function getLoggedInUser()
    {
        self::$skip2Factor = true;
        $user = parent::getLoggedInUser();
        self::$skip2Factor = false;
        return $user;
    }
}