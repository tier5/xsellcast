<?php

namespace App\Storage\NotificationSetting\NotificationBrand;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class NotificationBrandValidator extends LaravelValidator
{

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [],
        ValidatorInterface::RULE_UPDATE => [],
   ];
}
