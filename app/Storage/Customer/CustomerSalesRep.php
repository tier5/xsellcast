<?php namespace App\Storage\Customer;

use Illuminate\Database\Eloquent\Model;

class CustomerSalesRep extends Model
{

    protected $fillable = ['salesrep_id', 'customer_id'];

    protected $table = 'customer_salesreps';

    protected $rejected = false;

    public function salesrep()
    {
    	return $this->hasOne('App\Storage\SalesRep\SalesRep', 'id', 'salesrep_id');
    }

    public function customer()
    {
        return $this->hasOne('App\Storage\Customer\Customer', 'id', 'customer_id');
    }

    public function scopeWithApproved($query)
    {
        return $query->where('approved', true)->where('rejected', false);
    }

    public function scopeWithPending($query)
    {
        return $query->where('approved', false)->where('rejected', false);
    }    

    public function scopeWithRejected($query)
    {
        return $query->where('approved', false)->where('rejected', true);
    }

    public function getIsPendingAttribute()
    {

        return (!$this->approved && !$this->rejected);
    }
}