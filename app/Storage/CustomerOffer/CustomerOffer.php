<?php

namespace App\Storage\CustomerOffer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerOffer extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['customer_id', 'offer_id', 'salesrep_id', 'salesrep_approved'];

    public function customer()
    {
    	return $this->hasOne('App\Storage\Customer\Customer', 'id', 'customer_id');
    }

    public function offer()
    {
    	return $this->hasOne('App\Storage\Offer\Offer', 'id', 'offer_id');
    }

}