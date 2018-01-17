<?php

namespace App\Storage\Category;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Category extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [ 'name', 'id', 'opid' ];

    protected $table = 'categories';

    public function brands()
    {
        return $this->belongsToMany('App\Storage\Brand\Brand', 'brand_categories', 'category_id', 'brand_id');
    }

    public function scopeWithDealers($query)
    {
    	return $query->whereHas('brands', function($query){

    		$query->has('dealers');
    	});
    }
}
