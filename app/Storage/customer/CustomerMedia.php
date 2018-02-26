<?php

namespace App\Storage\CustomerMedia;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerMedia extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table='customer_medias';

    protected $fillable = ['customer_id', 'media_id'];

    public function customer()
    {
    	return $this->hasOne('App\Storage\Customer\Customer', 'id', 'customer_id');
    }

    public function media()
    {
    	return $this->hasOne('App\Storage\Media\Media', 'id', 'media_id');
    }

}