<?php

namespace Rhubarb\AuthenticationWithTwoFactorAuth;

use Rhubarb\AuthenticationWithTwoFactorAuth\Login\TwoFactorLogin;
use Rhubarb\Scaffolds\Authentication\Settings\ProtectedUrl;
use Rhubarb\Scaffolds\AuthenticationWithRoles\AuthenticationWithRolesModule;

class AuthenticationWithTwoFactorAuthModule extends AuthenticationWithRolesModule
{
    public function __construct($loginProviderClassName = null, $urlToProtect = '/', $loginUrl = '/login/')
    {
        parent::__construct();
        if ($loginProviderClassName !== null) {
            $protected = new ProtectedUrl(
                $urlToProtect,
                $loginProviderClassName,
                $loginUrl
            );
            $protected->loginLeafClassName = TwoFactorLogin::class;
            $this->registerProtectedUrl($protected);
        }
    }
}