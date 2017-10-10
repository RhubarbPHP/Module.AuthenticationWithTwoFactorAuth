<?php

namespace Rhubarb\AuthenticationWithTwoFactorAuth\Login;

use Rhubarb\AuthenticationWithTwoFactorAuth\LoginProviders\TwoFactorLoginProvider;
use Rhubarb\Leaf\Leaves\Leaf;
use Rhubarb\Leaf\Leaves\LeafModel;

class TwoFactorAuth extends Leaf
{
    /** @var TwoFactorAuthModel */
    protected $model;

    public function __construct($name = null, $initialiseModelBeforeView = null)
    {
        parent::__construct($name, $initialiseModelBeforeView);
    }

    protected function getViewClass()
    {
        return TwoFactorAuthView::class;
    }

    /**
     * Should return a class that derives from LeafModel
     *
     * @return LeafModel
     */
    protected function createModel()
    {
        return new TwoFactorAuthModel();
    }

    protected function onModelCreated()
    {
        $this->model->twoFactorConfirmEvent->attachHandler(function() {
            $loginProvider = TwoFactorLoginProvider::singleton();
            $loginProvider->validateTwoFactorInput($this->model->verificationCode);
        });
    }
}