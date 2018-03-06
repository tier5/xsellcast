<?php

namespace App\Storage\NotificationSetting\NotificationNews;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class NotificationNewsValidator extends LaravelValidator
{

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [],
        ValidatorInterface::RULE_UPDATE => [],
   ];
}
