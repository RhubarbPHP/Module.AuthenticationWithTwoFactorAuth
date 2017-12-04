<?php

namespace Rhubarb\AuthenticationWithTwoFactorAuth;

use ParagonIE\ConstantTime\Base32;
use Rhubarb\Crown\Encryption\HashProvider;
use Rhubarb\Stem\Schema\Columns\StringColumn;
use Rhubarb\Stem\Schema\ModelSchema;

class User extends \Rhubarb\Scaffolds\AuthenticationWithRoles\User
{
    protected function extendSchema(ModelSchema $schema)
    {
        $schema->addColumn(
            new StringColumn('TFASecret', 255),
            new StringColumn('MobileNumber', 50)
        );

        parent::extendSchema($schema);
    }

    protected function beforeSave()
    {
        if ($this->isNewRecord()) {
            $hashProvider = HashProvider::getProvider();
            $secret = $hashProvider->createHash($this->UserID, random_bytes(20));
            $this->TFASecret = Base32::encodeUpper($secret);
        }

        parent::beforeSave();
    }
}