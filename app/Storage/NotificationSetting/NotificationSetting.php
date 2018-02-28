<?php

namespace App\Storage\NotificationSetting;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class NotificationSetting extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['notification_type', 'customer_id'];//1 = new features and news, 2= National Offers , 3 = Brand Associates Offer

    protected $table = 'customer_notification_settings';

   public function customer()
    {
        return $this->hasOne('App\Storage\Customer\Customer', 'id', 'customer_id');
    }
}