<?php

namespace App\Storage\DealersCategory;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Cviebrock\EloquentSluggable\Sluggable;

class DealersCategory extends Model implements Transformable
{
    use TransformableTrait, Sluggable;

    protected $fillable = ['name', 'slug'];

    protected $table = 'dealers_category';

    public function dealers()
    {
    	return $this->belongsToMany('App\Storage\Dealer\Dealer', 'dealers_category_relation', 'category_id', 'dealer_id');    	
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }    
}