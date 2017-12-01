<?php

namespace Rhubarb\AuthenticationWithTwoFactorAuth\Login;

use Rhubarb\AuthenticationWithTwoFactorAuth\LoginProviders\TwoFactorLoginProvider;
use Rhubarb\Crown\Events\Event;
use Rhubarb\Scaffolds\Authentication\Leaves\Login;

/**
 * Class TwoFactorLogin
 * @package Rhubarb\AuthenticationWithTwoFactorAuth\Login
 * @property TwoFactorLoginModel $model
 */
class TwoFactorLogin extends Login
{
    protected $codePrompt = false;

    protected function createModel()
    {
        return new TwoFactorLoginModel();
    }

    protected function onModelCreated()
    {
        parent::onModelCreated();
        /** @var TwoFactorLoginProvider $loginProvider */
        $loginProvider = $this->getLoginProvider();
        $this->model->codeAttempted = $loginProvider->codeAttempted;
        $this->model->verificationCode = $loginProvider->verificationCode;
        $this->model->twoFactorVerified = $loginProvider->isTwoFactorVerified();
        $this->model->verifyCodeEvent = new Event();
        $this->model->verifyCodeEvent->attachHandler(function () {
            /** @var TwoFactorLoginProvider $loginProvider */
            $loginProvider = $this->getLoginProvider();
            $loginProvider->validateCode($this->model->Code);
            $this->onSuccess();
        });
    }


    protected function getViewClass()
    {
        return TwoFactorLoginView::class;
    }

    protected function onSuccess()
    {
        /** @var TwoFactorLoginProvider $loginProvider */
        $loginProvider = $this->getLoginProvider();
        if (!$loginProvider->isTwoFactorVerified()) {
            if (!$loginProvider->codeSent) {
                $loginProvider->createAndSendCode();
            }
            $this->model->promptForCode = true;
            return clone $this;
        } else {
            parent::onSuccess();
        }
    }
}