<?php

namespace App\Storage\OfferTag;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class OfferTag extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['offer_id', 'tag'];

    protected $table = "offer_tags";

    public function offer()
    {
    	return $this->hasOne('App\Storage\Offer\Offer', 'id', 'offer_id');
    }
}
