<?php

namespace App\Storage\NotificationSetting\NotificationBrandAssociates;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class NotificationBrandAssociates extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['customer_id','salesrep_id','status'];

    protected $table = 'customer_notification_salesrep';

   public function customer()
    {
        return $this->hasOne('App\Storage\Customer\Customer', 'id', 'customer_id');
    }
     public function salesrep()
    {
        return $this->hasOne('App\Storage\SalesRep\SalesRep', 'id', 'salesrep_id');
    }
}