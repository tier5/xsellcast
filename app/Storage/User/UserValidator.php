<?php

namespace App\Storage\User;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class UserValidator extends LaravelValidator
{

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
        	'email' => 'unique:users,email'
        ],
        ValidatorInterface::RULE_UPDATE => [],
   ];
}
