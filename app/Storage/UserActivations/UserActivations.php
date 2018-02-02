<?php

namespace App\Storage\UserActivations;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserActivations extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    protected $fillable = ['user_id', 'token'];

    protected $table = 'user_activations';
}