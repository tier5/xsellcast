<?php

namespace App\Storage\Offer;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Storage\Offer\OfferRepository;
use App\Storage\Offer\Offer;
use App\Storage\Offer\OfferValidator;
use App\Storage\Offer\OfferPresenter;
use App\Storage\SalesrepOffer\SalesrepOffer;
use App\Storage\Media\Media;
use App\Storage\Brand\Brand;

/**
 * Class OfferRepositoryEloquent
 * @package namespace App\Storage\Offer;
 */
class OfferRepositoryEloquent extends BaseRepository implements OfferRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Offer::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return OfferValidator::class;
    }

    public function presenter()
    {

        return OfferPresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Gets offers added to lookbook by customer.
     *
     * @param      Integer  $customer_id  The customer identifier
     *
     * @return     This.
     */
    public function getByCustomer($customer_id)
    {

        $model = $this->model
            ->whereHas('customers', function($query) use($customer_id){
                $query->where('customer_id', $customer_id);
                $query->where('added', true);
            });

        $this->model = $model;

        return $this;
    }

    public function setOfferToCustomer_deprecated($offer_id, $customer_id)
    {
        return $this->model->find($offer_id)->setCustomer($customer_id);
    }

    public function createForCsr($param)
    {
        $offer = $this->skipPresenter()->create($param);

        if(!is_array($param['media'])){
            $param['media'] = array(0);
        }

        if(!$param['thumbnail_id'] || $param['thumbnail_id'] == ''){

           $param['thumbnail_id'] = Media::whereIn('id', $param['media'])->where('type', 'image')->first()->id;
        }

        $this->createOfferMedia($offer, $param['media'], $param['thumbnail_id']);

        return $offer;
    }

    public function createForSalesRep($param, $salesrep_id)
    {
        $offer = $this->skipPresenter()->create($param);

        if(!is_array($param['media'])){
            $param['media'] = array(0);
        }

        if(!$param['thumbnail_id'] || $param['thumbnail_id'] == ''){

           $param['thumbnail_id'] = Media::whereIn('id', $param['media'])->where('type', 'image')->first()->id;
        }

        $this->createOfferMedia($offer, $param['media'], $param['thumbnail_id']);

        $count = SalesrepOffer::where('offer_id', $offer->id)->where('salesrep_id', $salesrep_id)->count();

        if($count < 1){
            $pivot = new SalesrepOffer();
            $pivot->offer_id = $offer->id;
            $pivot->salesrep_id = $salesrep_id;
            $pivot->save();
        }

        return $offer;
    }

    public function createOfferMedia($offer, $media_ids, $thumb_id)
    {
        $offer->setMeta('media', $media_ids);
        $offer->setMeta('thumbnail_id', $thumb_id);
        $offer->save();

        return $this;
    }

    /**
     * Get offers only that is related to BA.
     *
     * @param      SalesRep $salesrep  The salesrep
     * @param      String  $type      The type
     *
     * @return     self
     */
    public function ofSalesRepOrAuthorType($salesrep, $type = null)
    {
        $dealerIds = $salesrep->dealers()->lists('dealers.id')->toArray();

        $this->model = $this->model->where(function($query) use($salesrep, $dealerIds){
            $query->whereHas('salesrep', function($query) use($salesrep){
                $query->where('user_salesreps.id', $salesrep->id);
            })->orWhere(function($query) use($dealerIds){
                $query->inDealers($dealerIds);
                $query->where('author_type', '!=', 'custom');
            });
        });

        if($type){
            $this->ofAuthorType($type);
        }

        return $this;
    }

    /**
     * Filter offer by author type.
     *
     * @param      String  $type   The type
     *
     * @return     self
     */
    public function ofAuthorType($type)
    {
        if(!$type){
            return $this;
        }

        $this->model = $this->model->where('author_type', $type);

        return $this;

    }

    public function createOne($data, $author_type = 'custom')
    {
        $brand = Brand::find($data['brand_id']);

        if(!$brand)
        {
            abort(422, 'Invalid brand ID.');
        }

        $offer = $this->skipPresenter()->create([
            'contents'      => $data['contents'],
            'status'        => $data['status'],
            'title'         => $data['title'],
            'wpid'          => $data['wpid'],
            'author_type'   => $author_type
        ]);

        $offer->brands()->save($brand);

        return $this->skipPresenter(false)->find($offer->id);
    }

    public function offerByBrand($brand_id){
        $this->model = $this->model
            ->join('brand_offers', 'brand_offers.offer_id', '=', 'offers.id')
            ->where('brand_offers.brand_id',$brand_id)
            ->select('offers.*');
            return $this->model;

    }
    public function offerByCaregory($category_id){
        $this->model = $this->model
            ->join('brand_offers', 'brand_offers.offer_id', '=', 'offers.id')
            ->join('brand_categories', 'brand_categories.brand_id', '=', 'brand_offers.brand_id')
            ->where('brand_categories.category_id',$category_id)
            ->select('offers.*');
            return $this->model;

    }

    public function myOffers($dealerIds)
    {
        $this->model = $this->model->where(function($query) use($dealerIds){
            $query->Where(function($query) use($dealerIds){
                $query->inDealers($dealerIds);
                // $query->where('author_type', '!=', 'custom');
            });
        });

        // if($type){
        //     $this->ofAuthorType($type);
        // }

        return $this;
    }


}
