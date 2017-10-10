<?php

namespace Rhubarb\AuthenticationWithTwoFactorAuth\Login;

use Rhubarb\Leaf\Controls\Common\Buttons\Button;
use Rhubarb\Leaf\Controls\Common\Text\TextBox;
use Rhubarb\Leaf\Views\View;

class TwoFactorAuthView extends View
{
    /** @var TwoFactorAuthModel */
    protected $model;

    public function createSubLeaves()
    {
        parent::createSubLeaves();

        $this->registerSubLeaf(
            new TextBox('verificationCode'),
            new Button('Confirm', 'Confirm', function() {
                $this->model->twoFactorConfirmEvent->raise();
            })
        );
    }

    public function printViewContent()
    {
        ?>
        <div class="c-alert">A text message with a 6-digit verification code was just sent to your phone. Please enter
            the code below.
        </div>
        <fieldset class="c-form c-form--inline">
            <div class="c-form__group">
                <label class="c-form__label">Verification Code</label>
                <?= $this->leaves['verificationCode']; ?>
            </div>

            <div class="c-form__actions">
                <?= $this->leaves['Confirm']; ?>
            </div>
        </fieldset>

        <?php
    }
}