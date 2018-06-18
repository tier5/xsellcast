<?php namespace App\Storage\Messenger;

use App\Storage\Customer\Customer;
use App\Storage\Customer\CustomerRepositoryEloquent;
use App\Storage\Customer\CustomerSalesRep;
use App\Storage\Messenger\Thread;
use App\Storage\Messenger\ThreadPresenter;
use App\Storage\Messenger\ThreadRepository;
use App\Storage\Messenger\ThreadValidator;
use App\Storage\SalesRep\SalesRep;
use Illuminate\Container\Container as Application;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ThreadRepositoryEloquent
 * @package namespace App\Storage\Messenger;
 */
class ThreadRepositoryEloquent extends BaseRepository implements ThreadRepository {
    protected $status = 'publish';

    protected $customer;

    public function __construct(Application $app) {
        parent::__construct($app);
        $this->customer = new CustomerRepositoryEloquent($app);
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model() {
        return Thread::class;
    }

    /**
     * Specify Validator class name
     *
     * @return mixed
     */
    public function validator() {

        return ThreadValidator::class;
    }

    public function presenter() {
        return ThreadPresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot() {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function allNotNotes($type = null) {
        $this->model = $this->model->whereNotIn('type', ['note']);

        if ($type) {
            $this->model = $this->model->where('type', $type);
        }

        return $this;
    }

    /**
     * This is use for all messages
     *
     * This will exclude "note" and "new lead"
     */
    public function allMesssages($type, $user_id) {

        $this->model = $this->model->forUser($user_id)->whereNotIn('type', ['new_lead']);

        return $this->allNotNotes($type)->hasMessageNotMe($user_id);
    }

    /**
     * Pull thread which type is "new_lead"
     *
     * @return  $this
     */
    public function allLeads($user_id) {
        $this->model = $this->model->where('type', 'new_lead')->forUser($user_id);

        return $this;
    }

    /**
     * Get trend which don't
     *
     * @param      <type>   $user_id  The user identifier
     *
     * @return     boolean  True if has message not me, False otherwise.
     */
    public function hasMessageNotMe($user_id) {
        $this->model->whereHas('messages', function ($q) use ($user_id) {
            $q->where('user_id', '!=', $user_id);
        });

        return $this;
    }

    public function fromUser($user_id) {
        $model       = $this->model->forUser($user_id);
        $this->model = $model;

        return $this;
    }

    public function fromUserIn($user_ids) {
        $this->model = $this->model->whereHas('users', function ($q) use ($user_ids) {
            $q->whereIn('user_id', $user_ids);
        });

        return $this;
    }

    public function requestUnApprForCustUserAndInUser($cust_user_id, $salesrep_user_ids) {
        $this->model = $this->model->forRequestUnApproved();
        return $this->userSentMessages($cust_user_id)->fromUserIn($salesrep_user_ids);
    }

    public function setSelect() {
        $this->model = $this->model->select('messenger_threads.created_at', 'messenger_threads.updated_at', 'messenger_threads.subject', 'messenger_threads.type', 'messenger_threads.id', 'messenger_threads.deleted_at', 'messenger_messages.id AS message_id');
        return $this;
    }

    public function joinToMessage() {
        $this->model = $this->model->join('messenger_messages', 'messenger_messages.thread_id', '=', 'messenger_threads.id');

        return $this;
    }

    public function userSentMessages($user_id) {
        $type = null;
        $this->fromUser($user_id)->setSelect()->joinToMessage();
        $this->model = $this->model->whereHas('messages', function ($q) use ($user_id) {
            $q->where('user_id', $user_id);
        });
        return $this->setPresenter('App\Storage\Messenger\ThreadSentPresenter')->allNotNotes($type);
    }

    public function createDraftMessage($sender, $recepient, $body, $type, $subject = null, $offer_id = null) {
        $key     = config('lbt.message_stat')['draft']['key'];
        $body    = (!$body ? ' ' : $body);
        $subject = ($subject == '' ? ' ' : $subject);

        return $this->setQueryStatus($key)->createMessage($sender, $recepient, $body, $type, $subject, $offer_id);
    }

    public function createMessage($sender = null, $recepient, $body, $type, $subject = null, $offer_id = null, $brand_id = null) {
        $typeKey      = (isset(config('lbt.message_types')[$type]) ? $type : null);
        $participantB = null;

        if (!$typeKey) {
            abort(402, 'Invalid message type.');
        }

        if ($type != 'message' && $type != 'system') {
            $subject = 'N\A';

            if ((!$offer_id && !$brand_id) && !in_array($type, ['lead_reassign', 'new_lead'])) {
                abort(402, 'Offer or Brand field is required.');
            }
        } else {

            if (!$subject) {
                abort(402, 'Subject field is required.');
            }
        }

        if ($body == '') {
            //  abort(402, 'Body is required.');
        }

        $thread = $this->model->create([
            'subject' => $subject,
            'status'  => $this->getQueryStatus(),
            'type'    => $typeKey]);

        $message = new \App\Storage\Messenger\Message([
            'thread_id' => $thread->id,
            'user_id'   => ($sender ? $sender : 0),
            'body'      => $body]);

        if ($sender) {
            $participantA = new \Cmgmyr\Messenger\Models\Participant([
                'thread_id' => $thread->id,
                'user_id'   => $sender]);
        }

        if ($recepient) {
            /**
             * There could be time where recepient would be blank.
             *
             * @var        \
             */
            $participantB = new \Cmgmyr\Messenger\Models\Participant([
                'thread_id' => $thread->id,
                'user_id'   => $recepient]);
        }

        $thread->messages()->save($message);

        if (isset($participantA) && $participantA) {
            $thread->participants()->saveMany([$participantA]);
        }

        if ($participantB) {
            $thread->participants()->saveMany([$participantB]);
        }

        $thread->markAsRead($sender);

        if ($offer_id != null) {

            $thread->setOffer($offer_id);
            $thread->save();
        }
        if ($brand_id != null) {

            $thread->setBrand($brand_id);
            $thread->save();
        }
        /**
         * Create action / log / history
         */
        $this->createAction($sender, $thread);

        return $thread;
    }

    public function createSystemMessage($recepient, $body, $subject) {

        return $this->createMessage(null, $recepient, $body, 'system', $subject);
    }

    /**
     *
     * @param      Customer ID   $cust_user_id      The customer user identifier
     * @param      SalesRep ID   $salesrep_user_id  The salesrep user identifier
     *
     * @return     boolean
     */
    public function assignCustToSalesRep($cust_user_id, $salesrep_user_id, $offer_id, $type, $approved = false, $brand_id = null) {
        $salesRep = SalesRep::forUser($salesrep_user_id)->first();
        $customer = Customer::forUser($cust_user_id)->first();
        if ($offer_id) {
            $this->customer->setOfferToCustomer($offer_id, $customer, false, $type);
        }
        if ($brand_id) {
            $this->customer->setBrandToCustomer($brand_id, $customer, false, $type);
        }

        if (!$salesRep || !$customer) {
            return false;
        }

        //$this->customer->setOfferToCustomer($offer_id, $customer);

        /**
         * Check if prospect is already assigned to BA.
         *
         * @var        boolean
         */
        $isCustomer = $salesRep->hasCustomer($customer->id)->first();

        if ($isCustomer) {
            $custSalesRep = CustomerSalesRep::where([
                'customer_id' => $customer->id,
                'salesrep_id' => $salesRep->id])->first();

        } else {
            $custSalesRep = CustomerSalesRep::create([
                'customer_id' => $customer->id,
                'salesrep_id' => $salesRep->id,
                'approved'    => $approved]);
        }

        //  $d = $custSalesRep->allInfo()->create([
        //       'offer_id' => $offer_id,
        //       'request_type' => $type,
        //       'salesrep_approved' => $approved]);
        //   $custSalesRep->approved = $approved;
        //   $custSalesRep->

        return true;
    }

    public function newLeadNotification($cust_user_id, $salesrep_user_id) {
        $newLeadThread = $this->createMessage($cust_user_id, $salesrep_user_id, 'New Lead', 'new_lead', 'New Lead');

        return $newLeadThread;
    }

    /**
     * Sends a sales rep lead reassign.
     *
     * @param      <type>  $salesrep_user_id  The salesrep user identifier
     *
     * @return     Thread
     */
    public function sendSalesRepLeadReassign($cust_user_id, $salesrep_user_id) {
        $subject = config('lbt.message_types.lead_reassign.simple');
        return $this->createMessage($cust_user_id, $salesrep_user_id, $subject, 'lead_reassign', $subject);
    }

    /**
     * Sends a sales rep new lead reassign.
     *
     * @param      integer  $cust_user_id      The customer user identifier
     * @param      integer  $salesrep_user_id  The salesrep user identifier
     *
     * @return     Thread
     */
    public function sendSalesRepNewLeadReassign($cust_user_id, $salesrep_user_id) {
        $thread = $this->sendSalesRepLeadReassign($cust_user_id, $salesrep_user_id);
        $thread->setMeta('is_assign_to_other', true);
        $thread->save();

        return $thread;
    }

    /**
     * Creates an action.
     *
     * @param      <type>  $thread  The thread
     *
     * @return     App\Storage\UserAction\UserAction
     */
    public function createAction($user_id, $thread) {
        // dd($thread);
        $method = 'addOfferRequest' . ucfirst(strtolower($thread->type));
        $action = new \App\Storage\UserAction\UserAction();

        if (!method_exists($action, $method)) {

            return null;
        }

        return $action->{$method}($user_id, $thread);
    }

    public function getQueryStatus() {
        return $this->status;
    }

    public function setQueryStatus($status) {
        if (!isset(config('lbt.message_stat')[$status])) {

            abort(402, 'Invalid message status "' . $status . '".');
        }

        $this->status = $status;

        return $this;
    }

    public function draftOnly() {
        $this->model = $this->model->draftOnly();

        return $this;
    }

    public function searchRec($search = null) {
        if ($search) {
            $this->model = $this->model->forSearch($search);
        }

        return $this;
    }

    public function whereIn($field, $arr) {
        $this->model = $this->model->whereIn($field, $arr);

        return $this;
    }

    public function newLeadsList($user) {
        $threadModel = $this->orderBy('created_at', 'desc')->allLeads($user->id)->skipPresenter()->all();
        $salesrep    = $user->salesRep()->first();

        foreach ($threadModel as $k => $thread) {
            $customer    = $thread->users()->where('user_id', '!=', $user->id)->first()->customer;
            $custBaPivot = $customer->salesRepsPivot()->where('salesrep_id', $salesrep->id)->first();
            $isRejected  = ($custBaPivot ? $custBaPivot->rejected : null);
            $isApproved  = ($custBaPivot ? $custBaPivot->approved : null);

            /**
             * if rejected then remove to list.
             * else if relationship don't exits then remove
             * or if approved
             */
            if ($isRejected || !$custBaPivot || $isApproved) {
                $threadModel->forget($k);
            }

        }

        $threads = $this->orderBy('created_at', 'desc')->whereIn('id', $threadModel->lists('id')->toArray());

        return $threads;
    }

    public function newLeadsListUnreadCount($user) {
        $threads = $this->newLeadsList($user)->all();
        $userId  = $user->id;

        foreach ($threads as $k => $thread) {
            if (!$thread->isUnread($userId)) {
                $threads->forget($k);
            }
        }

        return $threads->count();
    }

    public function newLeadsListCount($user) {
        $threads = $this->newLeadsList($user)->all();

        return $threads->count();
    }
}
