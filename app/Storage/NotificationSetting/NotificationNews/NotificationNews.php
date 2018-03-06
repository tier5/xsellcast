<?php

namespace App\Storage\NotificationSetting\NotificationNews;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class NotificationNews extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['customer_id','status'];//1 = new features and news, 2= National Offers , 3 = Brand Associates Offer

    protected $table = 'customer_notification_news';

   public function customer()
    {
        return $this->hasOne('App\Storage\Customer\Customer', 'id', 'customer_id');
    }
}