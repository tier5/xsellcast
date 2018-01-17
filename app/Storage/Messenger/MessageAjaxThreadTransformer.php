<?php 

namespace App\Storage\Messenger;

use League\Fractal\TransformerAbstract;
use App\Storage\Messenger\Message;
use Auth;

/**
 * Class MessageAjaxThreadTransformer
 * 
 * This is use for sent message
 * 
 * @package namespace App\Storage\Messenger;
 */
class MessageAjaxThreadTransformer extends TransformerAbstract
{



    /**
     * Transform the \Message entity
     * @param \Message $model
     *
     * @return array
     */
    public function transform(Message $model)
    {
        $currUser             = Auth::user();
        $last_message         = $model;
        $thread               = $model->threadWithDrafts;
        $participants         = $thread->participants();
        $participantCurrModel = $participants->where('user_id', '!=', $currUser->id)->first();
        $participantNotMe     = ($participantCurrModel ? $participantCurrModel->user : null );

        if(!$currUser)
        {
            $currUser = $thread->creator();
        }

        $typeArgs       = config('lbt.message_types')[$thread->type] + ['key' => $thread->type];

        if($last_message){
            switch ($thread->type) {
                case 'lead_reassign':
                    $sendeName = 'The Xsellcast Team';
                    break;
                case 'system':
                    $sendeName = 'Xsellcast Support Team';
                    break;
                default:
                    $sendeName = ($participantNotMe ? $participantNotMe->firstname . ' ' . $participantNotMe->lastname : '' );
                    break;
            }

            $lastArr   = [
                'id'                => $last_message->id,
          //      'body'              => $last_message->body,
          //      'body_excerpt'      => str_limit($last_message->body, 80),
                'sender_name'       => $sendeName,
                'created_at'        => $last_message->local_created_at,
                'created_at_human'  => ($last_message->local_created_at->isToday() ? $last_message->local_created_at->format('m/d/Y h:i A') : $last_message->local_created_at->format('m/d/Y h:i A') )];    

            switch ($thread->type) {
                case 'system':
                    $lastArr['body']         = 'Welcome to Xsellcast! Here are some tips to get started.';
                    $lastArr['body_excerpt'] = 'Welcome to Xsellcast! Here are some tips to get started.';
                    break;
                default:
                    $lastArr['body']         = $last_message->body;
                    $lastArr['body_excerpt'] = str_limit($last_message->body, 80);
                    break;
            }

        }else{
            $lastArr = null;
        }

        return [
            'id'            => (int) $thread->id,

            /* place your other model properties here */
            'is_unread'     => $model->isUnread($currUser->id), //Make everything read. Since the current user is the author. //$thread->isUnread($currUser->id),
            'thread_status' => $thread->status,
            'last_message'  => $lastArr,
            'type'          => $typeArgs,
            'created_at'    => carbonToLocal($thread->created_at),
            'updated_at'    => carbonToLocal($thread->updated_at)
        ];
    }
}
