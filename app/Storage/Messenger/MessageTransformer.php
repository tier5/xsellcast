<?php

namespace App\Storage\Messenger;

use League\Fractal\TransformerAbstract;
use App\Storage\Messenger\Message;

/**
 * Class MessageTransformer
 * @package namespace App\Storage\Messenger;
 */
class MessageTransformer extends TransformerAbstract
{

    /**
     * Transform the \Message entity
     * @param \Message $model
     *
     * @return array
     */
    public function transform(Message $model)
    {
        $sender = $model->user;
        $receipient = $model->thread->participants()->where('user_id', '!=', $sender->id)->first()->user;
        $thread = $model->thread;

        return [
            'id'            => (int) $model->id,

            /* place your other model properties here */
            'body'          => $model->body,
            'sender'        => $sender,
            'recepient'     => $receipient,
            'thread_status' => $thread->status,
            'thread'        => $thread->id,           
            'created_at'    => $model->created_at,
            'updated_at'    => $model->updated_at,
            'is_read'       => (!$thread->isUnread($receipient->id))
        ];
    }
}
