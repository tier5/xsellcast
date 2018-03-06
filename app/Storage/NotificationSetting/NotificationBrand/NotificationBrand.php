<?php

namespace App\Storage\NotificationSetting\NotificationBrand;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class NotificationBrand extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['customer_id','brand_id','status'];

    protected $table = 'customer_notification_brand';

   public function customer()
    {
        return $this->hasOne('App\Storage\Customer\Customer', 'id', 'customer_id');
    }
     public function brand()
    {
        return $this->hasOne('App\Storage\Brand\Brand', 'id', 'brand_id');
    }
}