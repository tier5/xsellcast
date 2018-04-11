<?php namespace App\Storage\Messenger;

use Illuminate\Database\Eloquent\Model as Model;
use Prettus\Repository\Traits\TransformableTrait;
use Prettus\Repository\Contracts\Transformable;

class Appointment extends Model implements Transformable
{
	use TransformableTrait;

	protected $fillable = ['message_id', 'user_id'];

    protected $table = 'messenger_messages_appointment';
}