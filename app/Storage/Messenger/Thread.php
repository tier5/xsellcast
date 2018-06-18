<?php namespace App\Storage\Messenger;

use App\Storage\Messenger\ThreadPublishedScope;
use Cmgmyr\Messenger\Models\Thread as Model;
use Kodeine\Metable\Metable;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Thread extends Model implements Transformable {
    use TransformableTrait, Metable;

    protected $fillable = ['subject', 'type', 'status', 'request_approved'];

    protected $metaTable = 'messenger_thread_meta';

    protected $table = 'messenger_thread';

    protected $local_created_at = null;

    protected $local_updated_at = null;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot() {
        parent::boot();

        static::addGlobalScope(new ThreadPublishedScope);
    }

    /**
     * Show last message from a recepient not from current user.
     */
    public function lastMessage() {
        return $this->messages()
            ->orderBy('created_at', 'desc');
    }

    public function offer() {

        return \App\Storage\Offer\Offer::find($this->getMeta('offer_id'));
    }

    public function setOffer($offer_id) {
        $this->setMeta('offer_id', $offer_id);

        return $this;
    }

    public function brand() {

        return \App\Storage\Brand\Brand::find($this->getMeta('brand_id'));
    }

    public function setBrand($brand_id) {

        $this->setMeta('brand_id', $brand_id);

        return $this;
    }

    public function getCreatedAtAttribute($value) {
        $createdAt = new \Carbon\Carbon($value);

        return carbonToLocal($createdAt);
    }

    public function scopeWithMessage($query, $message_id) {
        return $query->whereHas('messages', function ($q) use ($message_id) {
            $q->where('id', $message_id);
        });
    }

    public function scopeDraftOnly($query) {

        return $query->withoutGlobalScopes([\App\Storage\Messenger\ThreadPublishedScope::class])->where('status', config('lbt.message_stat')['draft']['key']);
    }

    /**
     * Get thread with meta that is key is 'offer_id'
     *
     * @param      $query  The query
     *
     * @return     QueryBuilder
     */
    public function scopeForMetaOfferId($query) {
        return $query->forMeta()->where('key', 'offer_id');
    }

    /**
     * Get thread with meta that is key is 'brand_id'
     *
     * @param      $query  The query
     *
     * @return     QueryBuilder
     */
    public function scopeForMetaBrandId($query) {
        return $query->forMeta()->where('key', 'brand_id');
    }

    public function scopeSelectThreadFields($query) {

        return $query->select($this->table . '.*');
    }

    /**
     * Meta scope for easier join
     * -------------------------
     */
    public function scopeForMeta($query) {
        return $query->join($this->metaTable, $this->table . '.id', '=', $this->metaTable . '.' . $this->getMetaKeyName());
    }

    public function scopeForSearch($query, $key) {
        $key = urldecode($key);
        return $query->whereHas('messages', function ($query) use ($key) {
            $query->where('body', 'like', '%' . $key . '%');
        })->orWhereHas('participants', function ($q) use ($key) {
            $q->WhereHas('user', function ($q) use ($key) {
                $q->where('firstname', 'like', '%' . $key . '%');
                $q->orWhere('lastname', 'like', '%' . $key . '%');
            });
        });
    }

    public function scopeForRequestUnApproved($query) {
        return $query->where('request_approved', false)->forRequestType();
    }

    public function scopeForRequestType($query) {

        return $query->whereIn('type', ['appt', 'price', 'info', 'contact_me']);
    }

    public function getLocalCreatedAtAttribute() {
        $createdAt = $this->created_at; //new \Carbon\Carbon($this->created_at);

        return carbonToLocal($createdAt);
    }

    public function getLocalUpdatedAtAttribute() {
        $updatedAt = new \Carbon\Carbon($this->updated_at);

        return carbonToLocal($updatedAt);
    }

    public function pivotParticipant() {
        return $this->hasMany('App\Storage\CustomerMedia\MessageParticipants', 'thread_id', 'id');
    }
}

?>