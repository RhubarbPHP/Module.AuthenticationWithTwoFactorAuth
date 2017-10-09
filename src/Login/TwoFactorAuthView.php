<?php

namespace Rhubarb\AuthenticationWithTwoFactorAuth\Login;

use Rhubarb\Leaf\Controls\Common\Buttons\Button;
use Rhubarb\Leaf\Controls\Common\Text\TextBox;
use Rhubarb\Scaffolds\Authentication\Leaves\LoginView;

class TwoFactorAuthView extends LoginView
{
    public function createSubLeaves()
    {
        parent::createSubLeaves();

        $this->registerSubLeaf(
            new TextBox('AuthCode'),
            new Button('Confirm', 'Confirm')
        );
    }

    public function printViewContent()
    {
        if ($this->model->failed) {
            print '<div class="c-alert c-alert--error">Sorry, that code does not match the one we provided</div>';
        }

        ?>
        <fieldset class="c-form c-form--inline">
            <div class="c-form__group">
                <label class="c-form__label">Authorization Code</label>
                <?= $this->leaves["AuthCode"]; ?>
            </div>

            <div class="c-form__actions">
                <?= $this->leaves["Confirm"]; ?>
            </div>
        </fieldset>

        <?php
    }
}