<?php

namespace App\Storage\OfferTag;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Storage\OfferTag\OfferTagRepository;
use App\Storage\OfferTag\OfferTag;
use App\Storage\OfferTag\OfferTagValidator;

/**
 * Class OfferTagRepositoryEloquent
 * @package namespace App\Storage\OfferTag;
 */
class OfferTagRepositoryEloquent extends BaseRepository implements OfferTagRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return OfferTag::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return OfferTagValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function createUpdateToOffer($offer, $arr)
    {
        if(!$arr){
            return null;
        }

        if(is_array($arr)){
            $tagsList = $arr;
        }else{
            /**
             * Convert string list of tag title to array.
             *
             */
            $tagsList = explode(',', $arr);
        }
        /**
         * Delete all current tags.
         */
        $offer->tags()->delete();

        /**
         * Insert fresh list of tags
         */     

        foreach($tagsList as $tag)
        {
            $offer->tags()->create([
                'offer_id' => $offer->id,
                'tag'      => str_slug(strtolower($tag))]);
        }      

        return true;     
    }
}
