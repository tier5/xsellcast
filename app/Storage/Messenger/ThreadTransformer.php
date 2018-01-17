<?php namespace App\Storage\Messenger;

use League\Fractal\TransformerAbstract;
use App\Storage\Messenger\Thread;
use Auth;

/**
 * Class ThreadTransformer
 * @package namespace App\Storage\Messenger;
 */
class ThreadTransformer extends TransformerAbstract
{

    /**
     * Transform the \Thread entity
     * @param \Thread $model
     *
     * @return array
     */
    public function transform(Thread $model)
    {
        $currUser = Auth::user();
        $id = $model->id;
        if(!$currUser)
        {
            $currUser = $model->creator();
        }

        $last_message   = $model->lastMessage()->where('user_id', '!=', $currUser->id)->first();
        
        $typeArgs       = config('lbt.message_types')[$model->type] + ['key' => $model->type];

        if($last_message){
            $lastArr = [
                'id'                => $last_message->id,
                'body'              => $last_message->body,
                'body_excerpt'      => str_limit($last_message->body, 80),
                'sender_name'       => $last_message->user->firstname . ' ' . $last_message->user->lastname,
                'created_at'        => $last_message->created_at,
                'created_at_human'  => ($model->created_at->isToday() ? $model->created_at->format('m/d/Y h:i A') : $model->created_at->format('m/d/Y h:iA') )];    
        }else{
            $lastArr = null;
        }

        return [
            'id'            => (int)$id,

            /* place your other model properties here */
            'is_unread'     => $model->isUnread($currUser->id),
            'last_message'  => $lastArr,
            'type'          => $typeArgs,
            'created_at'    => $model->created_at,
            'updated_at'    => $model->updated_at
        ];
    }
}
