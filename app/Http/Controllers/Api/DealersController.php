<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Storage\Brand\BrandRepository;
use App\Storage\Dealer\DealerRepository;
use App\Storage\SalesRep\SalesRepRepository;

use App\Http\Requests\Api\DealerGetRequest;
use App\Http\Requests\Api\DealerLocationRequest;
use App\Http\Requests\Api\DealersGetRequest;

use App\Storage\ZipCodeApi\ZipCodeApi;

/**
 * @resource Dealer
 *
 * Dealer resource.
 */
class DealersController extends Controller
{
	protected $dealer;

    protected $brand;

    protected $sales_rep;

	public function __construct(DealerRepository $dealer, BrandRepository $brand,
        SalesRepRepository $sales_rep)
	{
		$this->dealer = $dealer;
        $this->brand = $brand;
        $this->sales_rep = $sales_rep;
	}

	/**
	 * All
	 *
	 * Get a list of dealers.
	 *
	 * @param      \Illuminate\Http\Request  $request  The request
	 *
	 * @return     Response
	 */
    public function index(DealersGetRequest $request)
    {
         try{
            $zip        = $request->get('zip');
            $limit      = $request->get('limit');
            $categoryId = $request->get('category', null);
            $per_page = $request->get('per_page', 20);
            $dealers    = $this->dealer;
            $where      = null;

            if($zip){
                $z = ZipCodeApi::getNearest($zip);
                $zips    = $z->getFoundZips();

                if(!$zips)
                {
                    return response(['data' => []]);
                }

                $zips    = (!$zips ? [$zip] : $zips);
                $dealers = $dealers->whereInZips($zips);
            }

            if($categoryId){
                $dealers = $dealers->withCategoryId($categoryId);
            }

            if($where){
                $dealers = $dealers->filter($where);
            }

            if($limit < 0){

                $dealers = $dealers->all();
            }else{
                $dealers = $dealers->paginate($per_page);
            }
            $data=[
                    'status'=>true,
                    'code'=>config('responses.success.status_code'),
                    'message'=>config('responses.success.status_message'),
                ];
                $data=array_merge($data,$dealers);

             return response()->json($data, config('responses.success.status_code'));
        }
        catch (\Exception $e) {
            // dd($e->getMessage());
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
     * Get a dealer by ID.
     * Return 404 if dealer doesn't exist.
     *
     * @param      \App\Http\Requests\Api\DealerGetRequest  $request    The request
     * @param      Integer                                  $dealer_id  The dealer identifier
     *
     * @return     Response
     */
    public function show(DealerGetRequest $request, $dealer_id)
    {
    	$dealer = $this->dealer->find($dealer_id);

		return response()
			->json($dealer);
    }
    public function dealerLocation(DealerLocationRequest $request)
    {
        try{
            $dealer_id=$request->dealer_id;
            $dealer = $this->dealer->find($dealer_id);
            return response()->json([
                    'status'=>true,
                    'code'=>config('responses.success.status_code'),
                    'data'=>$dealer,
                    'message'=>config('responses.success.status_message'),
                ], config('responses.success.status_code'));
        }
        catch (\Exception $e) {
            // dd($e->getMessage());
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
     * Brands
     *
     * Get brands related to a dealer.
     *
     * @param      \App\Http\Requests\Api\DealerGetRequest  $request    The request
     * @param      Integer                                   $dealer_id  The dealer identifier
     *
     * @return     Response
     */
    public function brands(DealerGetRequest $request, $dealer_id)
    {
        $brands = $this->brand->getByDealer($dealer_id)->paginate(20);

        return response()
            ->json($brands);
    }

    /**
     * Brand Associates
     *
     * Get brand associates related to a dealer.
     *
     * @param      \App\Http\Requests\Api\DealerGetRequest  $request    The request
     * @param      Integer                                  $dealer_id  The dealer identifier
     *
     * @return     Response
     */
    public function salesReps(DealerGetRequest $request, $dealer_id)
    {
        $salesRep = $this->sales_rep->getByDealer($dealer_id)->paginate(20);

        return response()
            ->json($salesRep);
    }
}
