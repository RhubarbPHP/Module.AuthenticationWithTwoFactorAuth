<?php

namespace Rhubarb\AuthenticationWithTwoFactorAuth\Login;

use Rhubarb\Crown\Exceptions\ForceResponseException;
use Rhubarb\Crown\LoginProviders\Exceptions\LoginDisabledException;
use Rhubarb\Crown\LoginProviders\Exceptions\LoginFailedException;
use Rhubarb\Crown\LoginProviders\LoginProvider;
use Rhubarb\Crown\Request\Request;
use Rhubarb\Crown\Request\WebRequest;
use Rhubarb\Crown\Response\RedirectResponse;
use Rhubarb\Scaffolds\Authentication\Leaves\Login;

class TwoFactorLogin extends Login
{
    protected function onModelCreated()
    {
        /** @var WebRequest $request */
        $request = Request::current();
        $redirectUrl = $request->get('rd');
        if ($redirectUrl) {
            $redirectUrl = urldecode($redirectUrl);
            $this->model->redirectUrl = $redirectUrl;
        }

        $this->model->attemptLoginEvent->attachHandler(function () {
            $login = LoginProvider::getProvider();

            try {
                if ($login->login($this->model->username, $this->model->password)) {

                    if ($this->model->rememberMe) {
                        $login->rememberLogin();
                    }

                    $login->sendTotpCode();
                    $this->onSuccess();
                }
            } catch (LoginDisabledException $er) {
                $this->model->disabled = true;
                $this->model->failed = true;
            } catch (LoginFailedException $er) {
                $this->model->failed = true;
            }
        });
    }

    protected function onSuccess()
    {
        throw new ForceResponseException(new RedirectResponse('twoFactorAuth/'));
    }
}