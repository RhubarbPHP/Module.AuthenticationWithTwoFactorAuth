<?php

namespace Rhubarb\AuthenticationWithTwoFactorAuth\Login;

use Rhubarb\AuthenticationWithTwoFactorAuth\LoginProviders\TwoFactorLoginProvider;
use Rhubarb\Scaffolds\Authentication\Leaves\Login;

class TwoFactorAuth extends Login
{
    public function __construct()
    {
        parent::__construct(TwoFactorLoginProvider::class);
    }

    protected function getViewClass()
    {
        return TwoFactorAuthView::class;
    }
}