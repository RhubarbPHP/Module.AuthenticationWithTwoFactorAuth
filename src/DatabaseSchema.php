<?php

namespace Rhubarb\AuthenticationWithTwoFactorAuth;

class DatabaseSchema extends \Rhubarb\Scaffolds\AuthenticationWithRoles\DatabaseSchema
{
    public function __construct()
    {
        parent::__construct();

        $this->addModel('User', User::class);
    }

}