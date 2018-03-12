<?php

namespace App\Storage\Messenger;

use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Cmgmyr\Messenger\Models\Message as Model;
use App\Storage\Messenger\MessagePublishedScope;
use Closure;

class MessageParticipants extends Model implements Transformable
{
    use TransformableTrait;

    protected $media = null;

    protected $local_created_at = null;

    protected $local_updated_at = null;
}