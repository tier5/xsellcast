<?php

namespace App\Storage\Messenger;

use League\Fractal\TransformerAbstract;
use App\Storage\Messenger\Thread;

/**
 * Class ThreadMessagesTransformer
 * @package namespace App\Storage\Messenger;
 */
class ThreadMessagesTransformer extends TransformerAbstract
{

    /**
     * Transform the \Thread entity
     * @param \Thread $model
     *
     * @return array
     */
    public function transform(Thread $model)
    {

        $typeArgs = config('lbt.message_types')[$model->type] + ['key' => $model->type];

        return [
            'id'            => (int) $model->id,
            /* place your other model properties here */
            'type'          => $typeArgs,
            'status'        => $model->status,
            'messages'      => $model->messages,
            'created_at'    => $model->created_at,
            'updated_at'    => $model->updated_at
        ];
    }
}
