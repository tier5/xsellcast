<?php namespace App\Storage\SalesRep;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class SalesRep extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['user_id', 'show_cellphone', 'show_officephone', 'show_email', 'cellphone', 'officephone', 'facebook', 'twitter', 'linkedin', 'avatar_id', 'job_title', 'email_work', 'email_personal', 'pinterest', 'instagram', 'youtube', 'opid'];

    protected $table = 'user_salesreps';

    protected $is_agreement = true;

    protected $local_created_at = null;

    protected $local_agreed_at = null;

    protected $has_social_profile = false;

    /**
     * Get current sales rep basic info(firstname, lastname and email).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
    	return $this->belongsTo('App\Storage\User\User', 'user_id', 'id');
    }

    public function customers()
    {
    	return $this->belongsToMany('App\Storage\Customer\Customer', 'customer_salesreps', 'salesrep_id', 'customer_id');
    }

    public function scopePendingCustomers()
    {
        return $this->customers()->whereHas('pivotOffers', function($q){
            $q->where('approved', false);
            $q->where('rejected', false);
        });
    }

    public function dealers()
    {
        return $this->belongsToMany('App\Storage\Dealer\Dealer', 'dealer_salesrep', 'salesrep_id', 'dealer_id');
    }    

    /**
     * Determines if it has customer.
     *
     * @param      interger  $customer_id  The customer identifier
     */
    public function hasCustomer($customer_id)
    {
        return $this->customers()->where('customer_id', $customer_id);
    }

    public function offers()
    {
        return $this->belongsToMany('App\Storage\Offer\Offer', 'salesrep_offers', 'salesrep_id', 'offer_id');
    }

    public function scopeForUser($query, $user_id)
    {

        return $query->whereHas('user', function($q) use($user_id){
            $q->where('id', $user_id);
        });
    }

    public function scopeForAcceptedAgreement($query)
    {
        return $query->whereHas('user', function($query){
            $query->meta()->where(function($query){
                $query->where('users_meta.key', '=', 'salesrep_agreement');
                $query->where('users_meta.value', '!=', '1');
            });
        });
    }

    public function getIsAgreementAttribute()
    {
        $meta = $this->user->getMeta('salesrep_agreement');
        
        if(is_bool($meta)){

            return $meta;
        }

        if(!$meta)
        {

            return false;
        }

        return true;
    }

    public function setTrueAgreement()
    {
        $this->user->setMeta('salesrep_agreement', true);

        return $this->user->save();
    }

    public function setFalseAgreement()
    {
        $this->user->setMeta('salesrep_agreement', false);
        
        return $this->user->save();
    }   

    public function setToPasswordChanged($boolean = true)
    {
        $this->password_changed = $boolean;
        $this->save();

        return $this;
    }

    public function getLocalCreatedAtAttribute()
    {
        return carbonToLocal($this->created_at);
    } 

    public function getLocalAgreedAtAttribute()
    {
        $meta = $this->user->getMeta('salesrep_agreement', true);
        
        if(isset($meta->value) && !$meta->value)
        {
            return null;
        }

        if(!isset($meta->updated_at) || !$meta->updated_at)
        {
            return null;
        }

        return carbonToLocal($meta->updated_at);
    }

    public function getHasSocialProfileAttribute()
    {
        return ( $this->facebook || $this->twitter || $this->linkedin || $this->pinterest || $this->instagram || $this->youtube);
    }

    public function customersPivot()
    {
        return $this->hasMany('App\Storage\Customer\CustomerSalesRep', 'salesrep_id', 'id');
    }    
}
