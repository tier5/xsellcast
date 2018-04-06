<?php

namespace App\Storage\Appointment;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class AppointmentValidator extends LaravelValidator
{

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [],
        ValidatorInterface::RULE_UPDATE => [],
   ];
}
