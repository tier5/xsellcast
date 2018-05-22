<?php

namespace App\Storage\Brand;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Brand extends Model implements Transformable {
    use TransformableTrait;
    use Sluggable;

    protected $fillable = ['name', 'parent_id', 'media_logo_id', 'description', 'catalog_url', 'media_ids', 'opid', 'wp_brand_id', 'slug', 'image_url', 'image_link', 'image_text'];

    protected $table = 'brands';

    protected $category;

    protected $logo;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable() {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    public function dealers() {
        return $this->belongsToMany('App\Storage\Dealer\Dealer', 'dealer_brands', 'brand_id', 'dealer_id');
    }

    public function offers() {
        return $this->belongsToMany('App\Storage\Offer\Offer', 'brand_offers', 'brand_id', 'offer_id');
    }

    public function categories() {
        return $this->belongsToMany('App\Storage\Category\Category', 'brand_categories', 'brand_id', 'category_id');
    }

    public function getCategoryAttribute() {
        return $this->categories->first();
    }

    public function getLogoAttribute() {
        if (!$this->media_logo_id) {
            return false;
        }

        $media = \App\Storage\Media\Media::find($this->media_logo_id);

        return $media->getOrigUrl();
    }

    public function salesReps() {
        $collect = collect([]);

        foreach ($this->dealers()->get() as $dealer) {
            foreach ($dealer->salesReps()->get() as $salesrep) {
                $collect->push($salesrep);
            }
        }

        return $collect;
    }

    public function getOffers() {

        return $this->offers->all();
    }

    public function getDealers() {

        return $this->dealers->all();
    }
}