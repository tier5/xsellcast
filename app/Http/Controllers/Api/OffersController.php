<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Offer\OfferRepository;
use App\Storage\Media\MediaRepository;
use App\Storage\Dealer\DealerRepository;

use App\Storage\Dealer\Dealer;

use App\Http\Requests\Api\OffersRequest;
use App\Http\Requests\Api\OffersShowRequest;
use App\Http\Requests\Api\OffersStorePostRequest;
use App\Http\Requests\Api\OffersDeleteRequest;
use App\Http\Requests\Api\OffersPutRequest;
use App\Http\Requests\Api\SimpleListGetRequest;

use App\Storage\LbtWp\WpConvetor;
use App\Storage\ZipCodeApi\ZipCodeApi;





/**
 * @resource Offer
 *
 * Offer resource.
 */
class OffersController extends Controller
{
	protected $offer;
    protected $dealer;
    protected $media;
	public function __construct(OfferRepository $offer, MediaRepository $media,DealerRepository $dealer)
	{
        $this->offer = $offer;
        $this->media = $media;
		$this->dealer = $dealer;
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
            $wp_brand_id=$request->get('wp_brand_id');
            $wp_category_id=$request->wp_category_id;
            $pre_page=$request->pre_page!=''?$request->pre_page:20;
            $offers=[];
            $offer = $this->offer;//->skipPresenter();
            $ip=$request->get('ip');
            $zip_code='';
            if($ip!=''){
                //1 get all zip codes using zip api
                $zip=new ZipCodeApi();
               //get zip form Ip
               $zip_ip= $zip->getZipByIP($ip);

               if($zip_ip->getZipCode()!=null){

                    $zip_code=$zip_ip->getZipCode();

                }else{
                    $msg='IP Address is invalid.';
                    return response()->json([
                       'status'=>false,
                        'code'=>config('responses.bad_request.status_code'),
                        'data'=>[],
                        'message'=> $msg,
                    ], config('responses.bad_request.status_code'));
                }
                 $zip_codes=$zip->getNearest($zip_code,200);
                    if($zip_codes->getFoundZips()!=null){
                        $delears_id=Dealer::whereIn('zip',$zip_codes->getFoundZips())->pluck('id');
                        $offer=$offer->dealerOffers($delears_id);
                    }
                     $offers=$offer->paginate($pre_page);
            }

            if($wp_brand_id!=''){

                $wp=new WpConvetor();
                $brand_id=$wp->getId('brand',$wp_brand_id);
                $offer=$offer->offerByBrand($brand_id);
                // $offer=$offer->skipPresenter()->brands()->where('brand_id',$brand_id);
                 $offers=$offer->paginate($pre_page)->toArray();
            }

            if($wp_category_id!=''){
                $wp=new WpConvetor();
                $category_id=$wp->getId('category',$wp_category_id);
                $offer=$this->offer->offerByCaregory($category_id);
                $offers=$offer->paginate($pre_page)->toArray();
            }

// echo '<pre>';
//         print_r($offers);

            $data=[
                    'status'=>true,
                    'code'=>config('responses.success.status_code'),
                    'message'=>config('responses.success.status_message'),
                    ];
            $data=array_merge($data,$offers);

             return response()->json($data, config('responses.success.status_code'));
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

     public function showOffer(OffersShowRequest $request)
    {

        try{
            $offer = $this->offer->find($request->offer_id);
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
        try{
            $data = $request->all();
            $brand_id='';
            $delear_id='';
            if(!isset($data['wpid']))
            {
                $data['wpid'] = null;
            }
            $wp_brand_id=$request->wp_brand_id;
            $wp=new WpConvetor();
            $brand_id=$wp->getId('brand',$wp_brand_id);
            $data['brand_id']=$brand_id;
            //1 Convert wp_dealer_id
            if($data['auther_type']=='dealer'){
            $wp_dealer_id=$request->wp_dealer_id;
            $wp=new WpConvetor();
            $dealer_id=$wp->getId('dealer',$wp_dealer_id);
            $data['dealer_id']=$dealer_id;
            }
            //2 upload image for thumb
            if(!empty($data['thumbnail'])){

                $file=$data['thumbnail'];

                $type = explode('/', $file->getClientMimeType());
                $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
                $baseName = basename($file->getClientOriginalName(), '.' . $ext);
                $fileName = $this->media->setUploadPath()->generateFilename($baseName, $ext);

                try {
                    $targetFile = $file->move($this->media->getUploadPath(), $fileName);
                    $thumb = $this->media->skipPresenter()->uploadImg($targetFile->getPathname(),[[150, 100]], false);
                    $data['thumbnail_id']=$thumb->id;
                    $data['media']=[$thumb->id];

                }
                catch (\Exception $e) {

                    $erroMsg = $this->media->errorMessage($file->getClientOriginalName());
                    $error = [
                        'title' => $erroMsg[0],
                        'body'  => $erroMsg[1]
                    ];
                    return response()->json([
                        'status'=>false,
                        'code'=>config('responses.bad_request.status_code'),
                        'data'=>$error,
                        'message'=> $erroMsg ,
                        ], config('responses.bad_request.status_code'));


                }
            }



            $offer = $this->offer->createOne($data,$data['auther_type']);

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
