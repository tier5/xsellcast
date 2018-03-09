<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Offer\OfferRepository;
use App\Http\Requests\Api\OffersRequest;
use App\Http\Requests\Api\OffersShowRequest;
use App\Http\Requests\Api\OffersStorePostRequest;
use App\Http\Requests\Api\OffersDeleteRequest;
use App\Http\Requests\Api\OffersPutRequest;
use App\Http\Requests\Api\SimpleListGetRequest;

/**
 * @resource Offer
 *
 * Offer resource.
 */
class OffersController extends Controller
{
	protected $offer;

	public function __construct(OfferRepository $offer)
	{
		$this->offer = $offer;
	}

	/**
	 * All
	 *
	 * Get a list of offers.
	 *
	 * @param      \App\Http\Requests\Api\OffersRequest  $request  The request
	 *
	 * @return     Response
	 */
    public function index(SimpleListGetRequest $request)
    {
    	try{
            $brand_id=$request->brand_id;
            $category_id=$request->category_id;
            $offer = $this->offer->skipPresenter();

            if($brand_id!=''){
            $offer=$this->offer->offerByBrand($brand_id);
            }

            if($category_id!=''){
            $offer=$this->offer->offerByCaregory($category_id);
            }
            $offers=$offer->paginate(20);


            return response()->json([
                    'status'=>true,
                    'code'=>config('responses.success.status_code'),
                    'offers'=>$offers,
                    'message'=>config('responses.success.status_message'),
                ], config('responses.success.status_code'));
        }
        catch (\Exception $e) {
            return response()->json([
                'status'=>false,
                'code'=>config('responses.bad_request.status_code'),
                'data'=>null,
                'message'=>$e->getMessage()
            ],
                config('responses.bad_request.status_code')
            );
        }
    }

    /**
     * Single
     *
     * Get an offer by ID.
     * Return 404 if offer doesn't exist.
     *
     * @param      \App\Http\Requests\Api\OffersShowRequest  $request    The request
     * @param      Integer                                  $offer_id  The offer identifier
     *
     * @return     Response
     */
    public function show(OffersShowRequest $request, $offer_id)
    {

        try{
            $offer = $this->offer->find($offer_id);
            return response()->json([
                    'status'=>true,
                    'code'=>config('responses.success.status_code'),
                    'offers'=>$offer,
                    'message'=>config('responses.success.status_message'),
                ], config('responses.success.status_code'));
        }
        catch (\Exception $e) {
            return response()->json([
                'status'=>false,
                'code'=>config('responses.bad_request.status_code'),
                'data'=>null,
                'message'=>$e->getMessage()
            ],
                config('responses.bad_request.status_code')
            );
        }
    }

    /**
     * Create
     *
     * Create an offer.
     *
     * @param      \App\Http\Requests\Api\OffersStorePostRequest  $request  The request
     *
     * @return     Response
     */
    public function store(OffersStorePostRequest $request)
    {
        $data = $request->all();

        if(!isset($data['wpid']) || !is_interger($data['wpid']))
        {
            $data['wpid'] = null;
        }

        $offer = $this->offer->createOne($data);

        return response()
            ->json($offer);
    }

    /**
     * Delete
     *
     * Delete an existing offer.
     *
     * @param      \App\Http\Requests\Api\OffersDeleteRequest  $request  The request
     *
     * @return     Response
     */
    public function destroy(OffersDeleteRequest $request)
    {
        $offer = $this->offer->skipPresenter()->find($request->get('offer_id'));
        $offer->customers()->detach();
        $offer->brands()->detach();
        $offer->salesrep()->detach();
       // $offer->pivotCustomers()->detach();
        // $offer->tags()->detach();
        $offer->delete();

        return response()->json(['data' => 1]);
    }

    /**
     * Update
     *
     * Update an existing offer.
     *
     * @param      \App\Http\Requests\Api\OffersPutRequest  $request  The request
     *
     * @return     Response
     */
    public function update(OffersPutRequest $request)
    {
        $offer = $request->get('offer');
        $ret = $this->offer->update($request->except('offer_id'), $offer->id);

        return response()->json(['data' => $ret]);
    }
}
