<?php namespace App\Storage\Messenger;

use League\Fractal\TransformerAbstract;
use App\Storage\Messenger\Message;
use Auth;
use App\Storage\Messenger\MessageAjaxThreadTransformer;
/**
 * Class MessageAjaxThreadSentTransformer
 * 
 * This is use for sent message
 * 
 * @package namespace App\Storage\Messenger;
 */
class MessageAjaxThreadSentTransformer extends TransformerAbstract
{
    /**
     * Transform the \Message entity
     * @param \Message $model
     *
     * @return array
     */
    public function transform(Message $model)
    {

        $trans = new MessageAjaxThreadTransformer;

        $arr = $trans->transform($model);
        $arr['is_unread'] = false;

        return $arr;
    }
}
