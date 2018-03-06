<?php

namespace App\Storage\NotificationSetting\NotificationBrandAssociates;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class NotificationBrandAssociatesValidator extends LaravelValidator
{

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [],
        ValidatorInterface::RULE_UPDATE => [],
   ];
}
