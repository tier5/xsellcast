<?php

namespace App\Storage\Brand;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Brand extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['name', 'parent_id', 'media_logo_id', 'description', 'catalog_url', 'media_ids', 'opid','wp_brand_id'];

    protected $table = 'brands';

    protected $category;

    protected $logo;

    public function dealers()
    {
    	return $this->belongsToMany('App\Storage\Dealer\Dealer', 'dealer_brands', 'brand_id', 'dealer_id');
    }

    public function offers()
    {
    	return $this->belongsToMany('App\Storage\Offer\Offer', 'brand_offers', 'brand_id', 'offer_id');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Storage\Category\Category', 'brand_categories', 'brand_id', 'category_id');
    }

    public function getCategoryAttribute()
    {
        return $this->categories->first();
    }

    public function getLogoAttribute()
    {
        if(!$this->media_logo_id)
        {
            return false;
        }

        $media = \App\Storage\Media\Media::find($this->media_logo_id);

        return $media->getOrigUrl();
    }

    public function salesReps()
    {
        $collect = collect([]);

        foreach($this->dealers()->get() as $dealer)
        {
            foreach($dealer->salesReps()->get() as $salesrep)
            {
                $collect->push($salesrep);
            }
        }

        return $collect;
    }
}