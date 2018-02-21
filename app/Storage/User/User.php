<?php namespace App\Storage\User;

use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Kodeine\Metable\Metable;
use Cmgmyr\Messenger\Traits\Messagable;

class User extends Authenticatable implements Transformable
{
	use TransformableTrait, EntrustUserTrait, Metable, Messagable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'email', 'password', 'is_email_notify','provider','provider_token'
    ];

    protected $metaTable = 'users_meta';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $table = "users";

    protected $avatar_url = null;

    protected $is_csr = false;

    protected $is_salesrep = false;

    protected $is_customer = false;

    public function salesRep()
    {
    	return $this->hasOne('App\Storage\SalesRep\SalesRep', 'user_id', 'id');
    }

    public function customer()
    {
        return $this->hasOne('App\Storage\Customer\Customer', 'user_id', 'id');
    }

    public function csr()
    {
        return $this->hasOne('App\Storage\Csr\Csr', 'id', 'id');
    }

    public function accountActivation()
    {
        return $this->hasOne('App\Storage\UserActivations\UserActivations', 'user_id', 'id');
    }

    public function scopeGetByActivation($query, $token)
    {
        return $query->whereHas('accountActivation', function($query) use($token){
            $query->where('token', $token);
        });
    }

    public function avatarId()
    {
        return $this->getMeta('avatar_media_id');
    }

    public function avatar()
    {
        return \App\Storage\Media\Media::where('id', $this->avatarId())->first();
    }

    public function getIsCsrAttribute()
    {
        return $this->hasRole('csr');
    }

    public function getIsSalesrepAttribute()
    {
        return $this->hasRole('sales-rep');
    }

    public function getIsCustomerAttribute()
    {
        return $this->hasRole('customer');
    }

    public function getAvatarUrlAttribute()
    {
        $avatar = $this->avatar();

        return ($avatar ? $avatar->getOrigUrl() : asset('img/blank-avatar.jpg') );
    }

    public function actions()
    {
        return $this->hasMany('App\Storage\UserAction\UserAction', 'id', 'user_id');
    }

    public function saveAsUnConfirmedInvited()
    {
        $this->status = 'invited_unconfirmed';
        $this->save();

        return $this;
    }

    public function saveAsActivated()
    {
        $this->status = 'active';
        $this->save();

        return $this;
    }

    public function isUnConfirmedInvited()
    {
        return ($this->status == 'invited_unconfirmed');
    }

    /**
     * Get all except:
     * Role = customer
     */
    public function scopeForCsrContact($query)
    {
        return $query->whereHas('roles', function($query){
            $query->whereIn('name', ['sales-rep', 'csr', 'customer']);
        });
    }

    public function scopeForSalesReps($query)
    {
        return $query->has('salesRep');
    }

    public function scopeForSalesRepsIn($query, $salesrep_ids)
    {
        return $query->whereHas('salesRep', function($q) use($salesrep_ids){
            $q->whereIn('id', $salesrep_ids);
        });
    }

    public function isFbUserNotPasswordSet()
    {
        $fbRegistered  = $this->getMeta('fb_registered');
        $isSalesrep    = $this->hasRole('sales-rep');
        $isFbSetPass   = $this->getMeta('fb_set_password');

        return ($isSalesrep && $fbRegistered && !$isFbSetPass);
    }

    public function isFbUserNoEmail()
    {
        $fbRegistered  = $this->getMeta('fb_registered');

        return ($fbRegistered && ($this->email == '' || !$this->email));
    }


}