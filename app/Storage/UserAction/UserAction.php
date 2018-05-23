<?php

namespace App\Storage\UserAction;

use Illuminate\Database\Eloquent\Model;
use Kodeine\Metable\Metable;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class UserAction extends Model implements Transformable {
    use TransformableTrait, Metable;

    protected $fillable = ['user_id', 'type', 'is_op_sent'];

    protected $metaTable = 'user_action_meta';

    protected $table = 'user_actions';

    protected $request_keys = ['offer_request_appt', 'offer_request_price', 'offer_request_info', 'offer_request_contact_me'];

    /**
     * Creates an action.
     *     * @param      User id  $user_id  The user identifier
     * @param      string  $type     The type
     * @param      array   $metas    The metas
     *
     * @return     UserAction
     */
    private function createAction($user_id, $type, $metas = array()) {

        if (!config('lbt.user_action_types.' . $type)) {

            abort('402', 'Invalid action type.');
        }

        $action = $this->create([
            'user_id' => $user_id,
            'type'    => $type]);

        foreach ($metas as $meta) {
            $action->setMeta([
                $meta['key'] => $meta['val']]);
        }

        if (!empty($metas)) {
            $action->save();
        }

        return $action;
    }

    public function addForThread($cust_user_id, $type, $thread) {
        if (!is_integer($thread)) {
            $thread_id = $thread->id;
        } else {
            $thread_id = $thread;
        }

        $meta = [
            ['key' => 'thread_id', 'val' => $thread_id],
        ];

        if (!is_integer($thread)) {
            $meta[] = ['key' => 'offer_id', 'val' => $thread->getMeta('offer_id')];
        }

        return $this->createAction($cust_user_id, $type, $meta);
    }

    public function addOfferRequestAppt($cust_user_id, $thread_id) {
        return $this->addForThread($cust_user_id, 'offer_request_appt', $thread_id);
    }

    public function addOfferRequestPrice($cust_user_id, $thread_id) {
        return $this->addForThread($cust_user_id, 'offer_request_price', $thread_id);
    }

    public function addOfferRequestContact_me($cust_user_id, $thread_id) {
        return $this->addForThread($cust_user_id, 'offer_request_contact_me', $thread_id);
    }

    public function addOfferRequestInfo($cust_user_id, $thread_id) {
        return $this->addForThread($cust_user_id, 'offer_request_info', $thread_id);
    }

    public function addOfferRequestMessage($cust_user_id, $thread_id) {
        return $this->addForThread($cust_user_id, 'direct_message', $thread_id);
    }

    public function addCustomerOffer($cust_user_id, $offer_id) {

        $meta = [
            ['key' => 'offer_id', 'val' => $offer_id],
        ];
        return $this->createAction($cust_user_id, 'added_offer', $meta);
    }
    public function removeCustomerOffer($cust_user_id, $offer_id) {

        $meta = [
            ['key' => 'offer_id', 'val' => $offer_id],
        ];
        return $this->createAction($cust_user_id, 'removed_offer', $meta);
    }

    public function user() {
        return $this->belongsTo('App\Storage\User\User', 'user_id', 'id');
    }

    public function scopeForCustomerActivity($query) {
        return $query->whereIn($this->table . '.type', ['offer_request_appt', 'offer_request_price', 'offer_request_info', 'added_offer', 'direct_message', 'offer_request_contact_me']);
    }

    public function scopeForCustomerRequest($query) {
        return $query->whereIn($this->table . '.type', ['offer_request_appt', 'offer_request_price', 'offer_request_info', 'offer_request_contact_me']);
    }

    /**
     * Filter data only for customer role
     */
    public function scopeForCustomerUser($query) {
        return $query->whereHas('user', function ($q) {
            $q->whereHas('roles', function ($q) {
                $q->where('name', 'customer');
            });
        });
    }

    public function scopeForSendingToOntraport($query) {
        return $query->where('is_op_sent', false);
    }

    public function scopeForCustomerAddedOffer($query) {
        return $query->whereIn($this->table . '.type', ['added_offer']);
    }

    public function scopeForSalesRepCustomer($query, $salesrep) {
        $query->whereHas('user', function ($query) use ($salesrep) {
            $customers = $salesrep->customers()->where('approved', true);
            if ($customers) {
                $userIds = $customers->lists('user_id')->toArray();
            } else {
                $userIds = [0];
            }
            $query->whereIn('user_id', $userIds);
        });
    }

    public function setRequestActivityAttribute($collect) {
        $this->attributes['request_activity'] = $collect;
    }

    /**
     * Set info if a action is request by customer to BA.
     */
    public function customerOfferRequest() {
        $requestKeys = $this->request_keys; //['offer_request_appt',     'offer_request_price', 'offer_request_info'];

        if (in_array($this->type, $requestKeys)) {
            $threadId = $this->getMeta('thread_id');
            $thread   = \App\Storage\Messenger\Thread::forMetaOfferId()->selectThreadFields()->find($threadId);

            if ($thread) {
                $collect = collect(['offer' => $thread->offer(), 'thread' => $thread]);
            } else {
                $collect = collect([]);
            }

        } elseif ($this->type == 'added_offer') {

            $offer   = \App\Storage\Offer\Offer::find($this->getMeta('offer_id'));
            $collect = collect(['offer' => $offer]);
        } elseif ($this->type == 'removed_offer') {

            $offer   = \App\Storage\Offer\Offer::find($this->getMeta('offer_id'));
            $collect = collect(['offer' => $offer]);
        } else {

            return null;
        }

        $this->setRequestActivityAttribute($collect);

        return $this;
    }

    /**
     * Meta scope for easier join
     * -------------------------
     */
    public function scopeForMeta($query) {
        return $query->join($this->metaTable, $this->table . '.id', '=', $this->metaTable . '.' . $this->getMetaKeyName());
    }

    public function scopeUniqueForOffer($query) {
        return $query->whereIn('type', $this->request_keys);
    }

    public function scopeInOfferIds($query, $ids) {

        return $query->forMeta()->where('user_action_meta.key', 'offer_id')->whereIn('user_action_meta.value', $ids)->select('user_actions.*');
    }

    public function scopeForUser($query, $user_id) {
        return $query->where('user_id', $user_id);
    }
}
