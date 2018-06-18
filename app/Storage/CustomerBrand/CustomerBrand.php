<?php

namespace App\Storage\CustomerBrand;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerBrand extends Model {
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['customer_id', 'brand_id'];

    public function customer() {
        return $this->hasOne('App\Storage\Customer\Customer', 'id', 'customer_id');
    }

    public function brand() {
        return $this->hasOne('App\Storage\Brand\Brand', 'id', 'brand_id');
    }

}