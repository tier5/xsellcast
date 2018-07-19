<?php

namespace App\Storage\Dealer;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Dealer extends Model implements Transformable {
    use TransformableTrait;

    protected $fillable = [
        'name',
        'address1',
        'address2',
        'city',
        'state',
        'phone',
        'fax',
        'website',
        'zip',
        'hours_of_operation',
        'description',
        'logo_media_id',
        'geo_lat',
        'geo_long',
        'county',
        'country_code',
        'outlet',
        'distributor_name',
        'rep_name',
        'rep_email',
        'wpid',

    ];

    protected $table = 'dealers';

    public function brands() {
        return $this->belongsToMany('App\Storage\Brand\Brand', 'dealer_brands', 'dealer_id', 'brand_id');
    }

    public function customers() {
        return $this->belongsToMany('App\Storage\Customer\Customer', 'customer_dealers', 'dealer_id', 'customer_id');
    }

    public function salesReps() {
        return $this->belongsToMany('App\Storage\SalesRep\SalesRep', 'dealer_salesrep', 'dealer_id', 'salesrep_id');
    }

    public function categories() {
        return $this->belongsToMany('App\Storage\DealersCategory\DealersCategory', 'dealers_category_relation', 'dealer_id', 'category_id');
    }
}