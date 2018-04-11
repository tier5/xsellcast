<?php

namespace App\Storage\Messenger;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Storage\Messenger\MessageRepository;
use App\Storage\Messenger\Message;
use App\Storage\Messenger\MessageValidator;
use App\Storage\Messenger\MessagePresenter;

/**
 * Class MessageRepositoryEloquent
 * @package namespace App\Storage\Messenger;
 */
class MessageRepositoryEloquent extends BaseRepository implements MessageRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Message::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return MessageValidator::class;
    }

    public function presenter()
    {
        return MessagePresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function byUser($user_id)
    {
        $this->model = $this->model->where('messenger_messages.user_id', $user_id);

        return $this;
    }

    public function receivedByUser($user_id, $search = null)
    {
        $this->model = $this->model->whereHas('thread', function($q) use($search, $user_id){
            //$q->draftOnly();
            $q->where('messenger_threads.type', '!=', 'note');
            $q->whereHas('participants', function($q) use($user_id){
                $q->where('user_id', $user_id);
            });
        })
        ->where('user_id', '!=', $user_id);

        return $this;
    }

    public function sentByUser($user_id, $search = null)
    {
        $this->model = $this->model->whereHas('thread', function($q) use($search, $user_id){
            //$q->draftOnly();
            $q->where('messenger_threads.type', '!=', 'note');
            $q->whereHas('participants', function($q) use($user_id){
                $q->where('user_id', $user_id);
            });
        })
        ->where('user_id', '=', $user_id);

        return $this;
    }

    public function queryDirectMessage()
    {
        $this->model->whereHas('thread', function($q){
            $q->where('type', 'message');
        });

        return $this;
    }

    public function queryApptMessage()
    {
        $this->model->whereHas('thread', function($q){
            $q->where('type', 'appt');
        });

        return $this;
    }

    public function queryPriceMessage()
    {
        $this->model->whereHas('thread', function($q){
            $q->where('type', 'price');
        });

        return $this;
    }

    public function queryInfoMessage()
    {
        $this->model->whereHas('thread', function($q){
            $q->where('type', 'info');
        });

        return $this;
    }

    public function draftQuerySearch($search = null)
    {
        $this->model = $this->model->whereHas('thread', function($q) use($search){
            $q->draftOnly();
         //   $q->where('messenger_threads.type', '!=', 'note');

            if($search){
                $search = '%' . $search . '%';

                $q->where('body', 'like', $search)
                    ->where('subject', $search)
                    ->orWhereHas('participants', function($query) use($search){
                        $query->WhereHas('user', function($query) use($search){
                            $query->where('firstname', 'like', $search);
                            $query->orWhere('lastname', 'like', $search);
                        });
                    });
            }
        });

        return $this;
    }

    public function notNote()
    {
        $this->model = $this->model->whereHas('thread', function($q){
            $q->where('messenger_threads.type', '!=', 'note');
        });

        return $this;
    }

    public function useThreadAjaxPresenter()
    {
        $this->setPresenter('App\Storage\Messenger\MessageAjaxThreadPresenter');

        return $this;
    }

    public function useIsReadThreadAjaxPresenter()
    {
        $this->setPresenter('App\Storage\Messenger\MessageAjaxThreadSentPresenter');

        return $this;
    }

    /**
     * Pull only draft messages.
     *
     * @return
     */
    public function draftOnly()
    {
       $this->model = $this->model->draftOnly();
        return $this;
    }

    public function searchRec($search = null)
    {
        if($search){
            $this->model = $this->model->forSearch($search);
        }

        return $this;
    }

    public function allMessages($search, $user_id, $type = null, $sent = false, $draft_only = false)
    {
        $this->model = $this->model->where(function($query) use($user_id, $type, $sent, $draft_only, $search){

            $query->allMessages(function($query) use($draft_only, $search){
                if($draft_only)
                {
                    $query->draftOnly();
                }

                if($search)
                {
                    $query->forSearch($search);
                }
            });

            if(!$sent){
                if(!$draft_only)
                {
                    $query->where('user_id', '!=', $user_id);
                }

                $query->allMessagesForUser($user_id, function($query) use($draft_only, $search){
                    if($draft_only)
                    {
                        $query->draftOnly();
                    }

                    if($search)
                    {
                        $query->forSearch($search);
                    }
                });
            }else{

                $query->allMessagesSentByUser($user_id, function($query) use($draft_only, $search){
                    if($draft_only)
                    {
                        $query->draftOnly();
                    }

                    if($search)
                    {
                        $query->forSearch($search);
                    }
                });
            }

            if($type){
                $query->forType($type, function($query) use($draft_only, $search){
                    if($draft_only)
                    {
                        $query->draftOnly();
                    }

                    if($search)
                    {
                        $query->forSearch($search);
                    }
                });
            }

        });

        return $this;
    }

    public function listWithApprovedUnread($user, $type)
    {
        $messages = $this->model->allMessages()
            ->unReadForUser($user->id)
            ->allMessagesForUser($user->id)
            ->forType($type)
            ->where('user_id', '!=', $user->id)
            ->get();

        foreach($messages as $k => $message)
        {
            if($message->user && $message->user->is_customer && $user->is_salesrep)
            {

                $hasPending = $message->user->customer->salesRepsPivot()->where('salesrep_id', $user->salesrep->id)->withPending()->count();

                if($hasPending > 0)
                {
                    //Remove a prospect message to BA when match is still pending.
                    $messages->forget($k);
                }
            }

        }

        return $messages;
    }

    public function listWithApprovedAll($user, $type)
    {
        $isSalesRep = $user->is_salesrep;
        $messages   = $this->model
            ->allMessagesForUser($user->id)
            ->whereHas('reads', function($q) use($user){

                $q->where('user_id', $user->id);
            }, '<', 1)
            ->forType($type)
            ->where('user_id', '!=', $user->id)
            ->get();

        foreach($messages as $k => $message)
        {
            if($message->user && $message->user->is_customer && $isSalesRep)
            {

                $hasPending = $message->user->customer->salesRepsPivot()->where('salesrep_id', $user->salesrep->id)->withPending()->count();

                if($hasPending > 0)
                {
                    //Remove a prospect message to BA when match is still pending.
                    $messages->forget($k);
                }
            }

        }

        return $messages;
    }

    public function allForSalesRepNoPending($user, $search = null)
    {
        $messagesModel = $this->skipPresenter()->allMessages($search, $user->id, $type)->all();

        foreach($messagesModel as $k => $message)
        {
            if($message->user && $message->user->is_customer && $user->is_salesrep)
            {
                /**
                 * Get all rejected and pending.
                 *
                 * @var        boolean
                 */
                $hasPending = $message->user->customer->salesRepsPivot()->where('salesrep_id', $user->salesrep->id)->withPending()->count();

                if($hasPending > 0)
                {
                    //Remove a prospect message to BA when match is still pending or rejected.
                    $messagesModel->forget($k);
                }
            }
        }

        $messagesIds = $messagesModel->lists('id')->toArray();
        $messages    = $this->allMessages($search, $user->id, $type)->scopeQuery(function($query) use($messagesIds){
                return $query->whereIn('id', $messagesIds);
            });

        return $messages;
    }

    public function baseGetAll($user, $search = null, $type = null)
    {
        $messagesModel = $this->skipPresenter()->useThreadAjaxPresenter()->allMessages($search, $user->id, $type)->all();

        foreach($messagesModel as $k => $message)
        {
            if($message->user && $message->user->is_customer && $user->is_salesrep)
            {
                /**
                 * Get all rejected and pending.
                 *
                 * @var        boolean
                 */
                $hasPending = $message->user->customer->salesRepsPivot()->where('salesrep_id', $user->salesrep->id)->withPending()->count();

                /**
                 * Don't exclude lead_reassign type even a prospect is not assign.
                 */
                if($hasPending > 0 && $message->thread->type != 'lead_reassign')
                {
                    //Remove a prospect message to BA when match is still pending or rejected.
                    $messagesModel->forget($k);
                }
            }
        }

        $messagesIds = $messagesModel->lists('id')->toArray();
        $messages    = $this->useThreadAjaxPresenter()
            ->allMessages($search, $user->id, $type)
            ->orderBy('created_at', 'desc')
            ->skipPresenter(false)
            ->scopeQuery(function($query) use($messagesIds){

                return $query->whereIn('id', $messagesIds);
            });

        return $messages;
    }

    public function isValidRequest($user_id,$message_id)
    {
        $this->model = $this->model->whereHas('thread', function($q) use($search, $user_id){

            $q->whereHas('participants', function($q) use($user_id,$message_id){
                $q->where('user_id', $user_id);
                $q->where('thread_id', $message_id);
            });
        })
        ->where('user_id', '=', $user_id);

        return $this;
    }

     public function listUnAppointed($user, $type)
    {
        $messages = $this->model->allMessages()
            ->unappointed($user->id)
            ->allMessagesForUser($user->id)
            ->forType($type)
            ->where('user_id', '!=', $user->id)
            ->get();

        foreach($messages as $k => $message)
        {
            if($message->user && $message->user->is_customer && $user->is_salesrep)
            {

                $hasPending = $message->user->customer->salesRepsPivot()->where('salesrep_id', $user->salesrep->id)->withPending()->count();

                if($hasPending > 0)
                {
                    //Remove a prospect message to BA when match is still pending.
                    $messages->forget($k);
                }
            }

        }

        return $messages;
    }
}
