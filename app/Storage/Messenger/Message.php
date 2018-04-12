<?php

namespace App\Storage\Messenger;

use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Cmgmyr\Messenger\Models\Message as Model;
use App\Storage\Messenger\MessagePublishedScope;
use Closure;

class Message extends Model implements Transformable
{
    use TransformableTrait;

    protected $media = null;

    protected $local_created_at = null;

    protected $local_updated_at = null;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

     //   static::addGlobalScope(new MessagePublishedScope);
    }

    public function scopeDraftOnly($query)
    {
		return $query->whereHas('thread', function($q){
            $q->draftOnly();
        });
    }

    public function scopeInThread($query, $thread_id)
    {
    	return $query->whereHas('thread', function($q) use($thread_id){
    		$q->where('id', $thread_id);
    	});
    }

    public function scopeInThreadDraftOnly($query, $thread_id)
    {
        return $query->whereHas('thread', function($q) use($thread_id){
            $q->where('id', $thread_id);
            $q->draftOnly();
        });
    }

    public function scopeForSearch($query, $key)
    {
        $key =  '%' . urldecode($key) . '%';
        return $query->where('body', 'like', $key)
            ->orWhereHas('thread', function($query) use($key){
                $query->where('subject', $key)
                ->orWhereHas('participants', function($query) use($key){
                    $query->WhereHas('user', function($query) use($key){
                        $query->where('firstname', 'like', $key);
                        $query->orWhere('lastname', 'like', $key);
                    });
                });
        });
    }

    public function reads()
    {
        return $this->hasMany('App\Storage\Messenger\Read', 'message_id', 'id');
    }

    public function threadWithDrafts()
    {
        return  $this->thread()->withoutGlobalScopes([ \App\Storage\Messenger\ThreadPublishedScope::class ]);
    }

    public function getMediaAttribute()
    {
        if(!$this->media_ids){

            return null;
        }

        return \App\Storage\Media\Media::whereIn('id', $this->media_ids)->get();
    }

    public function getMediaIdsAttribute($value)
    {
        if(!$value){

            return [];
        }

        return explode(',', $value);

    }

    public function getLocalCreatedAtAttribute()
    {
        $createdAt = new \Carbon\Carbon($this->created_at);

        return carbonToLocal($createdAt);
    }

    public function getLocalUpdatedAtAttribute()
    {
        $updatedAt = new \Carbon\Carbon($this->updated_at);

        return carbonToLocal($updatedAt);
    }

    /**
     * Mark a thread as read for a user.
     *
     * @param int $userId
     */
    public function markAsRead($user_id)
    {
        try {
            $read = $this->messageRead()->where('user_id', $user_id);

            if(!$read->first()){
                $read->create([
                    'message_id' => $this->id,
                    'user_id' => $user_id]);
            }

        } catch (ModelNotFoundException $e) { // @codeCoverageIgnore
            // do nothing
        }
    }

    /*
     *
     * See if the current thread is unread by the user.
     *
     * @param int $userId
     *
     * @return bool
     */
    public function isUnread($user_id)
    {
        try {
            $read = $this->messageRead()->where('user_id', $user_id)->first();

            if (!$read) {
                return true;
            }
        } catch (ModelNotFoundException $e) { // @codeCoverageIgnore
            // do nothing
        }

        return false;
    }

    public function messageRead()
    {
        return $this->hasMany('App\Storage\Messenger\Read', 'message_id', 'id');
    }

    public function scopeUnReadForUser($query, $user_id)
    {

        return $query->whereHas('messageRead', function($query) use($user_id){

            $query->where('user_id', $user_id);
        }, '<', 1);
    }

    public function scopeForType($query, $type, $closure = null)
    {
        return $query->whereHas('thread', function($query) use($type, $closure){
            $query->where('type', $type);

            if ($closure instanceof Closure) {
                call_user_func($closure, $query);
            }
        });
    }

    public function messageAppointment()
    {
        return $this->hasMany('App\Storage\Messenger\Appointment', 'message_id', 'id');
    }

    /**
     * Mark a thread as Appointed for a user.
     *
     * @param int $userId
     */
    public function markAsAppointed($user_id)
    {
        try {

            $read = $this->messageAppointment()->where('user_id', $user_id);

            if(!$read->first()){
                $read->create([
                    'message_id' => $this->id,
                    'user_id' => $user_id]);
            }
            return $read;

        } catch (ModelNotFoundException $e) { // @codeCoverageIgnore
            // do nothing
        }
    }

    /**
     * Get all messages except:
     * - note
     * - new_lead
     */
    public function scopeAllMessages($query, $closure = null)
    {
        return $query->whereHas('thread', function($query) use($closure){
            $query->whereNotIn('messenger_threads.type', ['new_lead', 'note']);

            if ($closure instanceof Closure) {
                call_user_func($closure, $query);
            }

        });
    }

    public function scopeAllMessagesForUser($query, $user_id, $closure = null)
    {
        return $query->whereHas('thread', function($query) use($user_id, $closure){
            $query->forUser($user_id);

            if ($closure instanceof Closure) {
                call_user_func($closure, $query);
            }
        });
    }

    public function scopeAllMessagesSentByUser($query, $user_id, $closure = null)
    {
        if ($closure instanceof Closure) {
            call_user_func($closure, $query);
        }

        return $query->where('user_id', $user_id);
    }

    public function scopeUnAppointed($query, $user_id)
    {

        return $query->whereHas('messageAppointment', function($query) use($user_id){

            $query->where('user_id', $user_id);
        }, '<', 1);
    }
    /**
     * End of class Message
     */
}