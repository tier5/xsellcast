<?php

namespace App\Storage\Customer;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Customer extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['zip', 'address1', 'address2', 'city', 'state', 'country', 'geo_long', 'geo_lat', 'wp_userid', 'homephone', 'cellphone', 'officephone', 'opid'];

    protected $available_phone;

    protected $table = 'user_customer';

    protected $has_pending = false;

    public function user()
    {
    	return $this->hasOne('App\Storage\User\User', 'id', 'user_id');
    }

    public function offers()
    {
    	return $this->belongsToMany('App\Storage\Offer\Offer', 'customer_offers', 'customer_id', 'offer_id');
    }

    public function medias()
    {
      return $this->belongsToMany('App\Storage\Media\Media', 'customer_medias', 'customer_id', 'media_id');
    }

    /**
     * This will select record from relationship table('customer_offers')
     */
    public function pivotOffers()
    {
        return $this->hasMany('App\Storage\CustomerOffer\CustomerOffer', 'customer_id', 'id');
    }

    public function salesReps()
    {
    	return $this->belongsToMany('App\Storage\SalesRep\SalesRep', 'customer_salesreps', 'customer_id', 'salesrep_id');
    }

    public function salesRepsPivot()
    {
        return $this->hasMany('App\Storage\Customer\CustomerSalesRep', 'customer_id', 'id');
    }

    public function pivotMedias()
    {
        return $this->hasMany('App\Storage\CustomerMedia\CustomerMedia', 'customer_id', 'id');
    }

    /**
     * Sets the offer.
     *
     * @param      integer  $offer_id  The offer identifier
     *
     * @return     App\Storage\CustomerOffer\CustomerOffer
     */
    public function setOffer($offer_id)
    {
        return $this->pivotOffers()
            ->updateOrCreate(['customer_id' => $this->id, 'offer_id' => $offer_id]);
    }
    public function setMedia($media_id)
    {
        return $this->pivotMedias()
            ->updateOrCreate(['customer_id' => $this->id, 'media_id' => $media_id]);
    }

    public function scopeForUserEmail($query, $email)
    {
        return $query->whereHas('user', function($q) use($email){
            $q->where('email', $email);
        });
    }

    public function scopeForUser($query, $user_id)
    {
        return $query->whereHas('user', function($q) use($user_id){
            $q->where('id', $user_id);
        });
    }

    public function scopeJoinUser($query)
    {
        return $query->join('users', 'users.id', '=', 'user_customer.user_id')->select('user_customer.*');
    }

    /**
     * Get user activies
     *
     * @return
     */
    public function activities()
    {
        return $this->hasManyThrough(
            'App\Storage\UserAction\UserAction', 'App\Storage\User\User', 'id', 'user_id', 'user_id');
    }

    /**
     * Check if has pending approval of BA's prospect.
     */
    public function getHasPendingAttribute()
    {
        $k = $this->salesRepsPivot()->withPending()->first();

        return ($k ? true : false);
    }

    public function getAvailablePhoneAttribute()
    {

      if($this->cellphone && $this->cellphone != '')
      {
        return $this->cellphone;
      }elseif($this->officephone && $this->officephone != '')
      {
        return $this->officephone;
      }elseif($this->homephone && $this->homephone != '')
      {
        return $this->homephone;
      }else{
        return null;
      }
    }

    /**
     * Update customer updated_at.
     */
    public function updatedAtNow()
    {
      $this->updated_at = \Carbon\Carbon::now();
      $this->save();
    }
}
