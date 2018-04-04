<?php

namespace App\Storage\Category;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Cviebrock\EloquentSluggable\Sluggable;

class Category extends Model implements Transformable
{
    use TransformableTrait;
    use Sluggable;

    protected $fillable = [ 'name', 'id', 'opid','wp_category_id','slug'];

    protected $table = 'categories';

     /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

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
