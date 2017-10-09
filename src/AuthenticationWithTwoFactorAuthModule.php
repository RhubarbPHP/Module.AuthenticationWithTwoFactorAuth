<?php

namespace Rhubarb\AuthenticationWithTwoFactorAuth;

use Rhubarb\Crown\LoginProviders\UrlHandlers\ValidateLoginUrlHandler;
use Rhubarb\Leaf\UrlHandlers\LeafCollectionUrlHandler;
use Rhubarb\Leaf\UrlHandlers\LeafUrlHandler;
use Rhubarb\Scaffolds\Authentication\UrlHandlers\CallableUrlHandler;
use Rhubarb\Scaffolds\AuthenticationWithRoles\AuthenticationWithRolesModule;

class AuthenticationWithTwoFactorAuthModule extends AuthenticationWithRolesModule
{
    public function __construct($loginProviderClassName = null, $urlToProtect = '/', $loginUrl = '/login/')
    {
        parent::__construct($loginProviderClassName, $urlToProtect, $loginUrl);
    }

    /** @var array ProtectedUrl[] */
    private $protectedUrls = [];

    protected function registerUrlHandlers()
    {
        foreach ($this->protectedUrls as $url) {

            $provider = $url->loginProviderClassName;

            $this->addUrlHandlers([
                $url->loginUrl => $login = new CallableUrlHandler(function () use ($url) {
                    $className = $url->loginLeafClassName;
                    return new $className($url->loginProviderClassName);
                }, [
                    $url->resetChildUrl => $reset = new LeafCollectionUrlHandler(
                        $url->resetPasswordLeafClassName,
                        $url->confirmResetPasswordLeafClassName
                    ),
                    $url->logoutChildUrl => $logout = new CallableUrlHandler(function () use ($url) {
                        $className = $url->logoutLeafClassName;
                        return new $className($url->loginProviderClassName);
                    }),
                    'twoFactorAuth/' => new LeafUrlHandler('Rhubarb\AuthenticationWithTwoFactorAuth\TwoFactorAuth'),
                ]),
                $url->urlToProtect => $protected =
                    new ValidateLoginUrlHandler($provider::singleton(), $url->loginUrl),
            ]);

            // Make sure that the login url handlers are given greater precedence than those of the application.
            $login->setPriority(10);
            $login->setName('login');

            $logout->setPriority(10);
            $logout->setName('logout');

            $reset->setPriority(10);
            $reset->setName('reset');

            $protected->setPriority(10);
        }
    }
}