<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Customer\CustomerRepository;
use App\Storage\NotificationSetting\NotificationNews\NotificationNewsRepository;
use App\Storage\NotificationSetting\NotificationBrand\NotificationBrandRepository;
use App\Storage\SalesRep\SalesRepRepository;
use App\Storage\Brand\BrandRepository;
// use App\Storage\Customer\Customer;
use App\Storage\Brand\Brand;
use App\Storage\SalesRep\SalesRep;


use App\Http\Requests\Api\CustomerNotificationNewsRequest;
use App\Http\Requests\Api\CustomerNotificationBrandRequest;
use App\Http\Requests\Api\CustomerNotificationBrandsRequest;
use App\Http\Requests\Api\CustomerNotificationBrandAssociateRequest;

/**
 * @resource Customer Notification Setting
 *
 * Customer Notification resource.
 */
class CustomerNotificationSettingController extends Controller
{

	protected $brand;

	protected $customer;

    protected $salesrep;
    protected $news;
    protected $notificationBrand;

	public function __construct(BrandRepository $brand, CustomerRepository $customer, SalesRepRepository $salesrep,NotificationNewsRepository $news,NotificationBrandRepository $notificationBrand)
	{
        $this->news = $news;
        $this->brand = $brand;

        $this->customer = $customer;
        $this->salesrep = $salesrep;
        $this->notificationBrand = $notificationBrand;

	}
    public function createGlobal(CustomerNotificationNewsRequest $request){

      try
        {
           $data=$request->all();
           $news=$this->news->isNews($data,1);
           if(empty($news)){
                $news=$this->news->createNews($data);
            }else{

                $news=$this->news->updateNews($news,$data);
            }
                 return response()->json([
                    'status'=>true,
                    'code'=>config('responses.success.status_code'),
                    'data'=> $news,
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
    public function createBrand(CustomerNotificationBrandRequest $request){

	  try
        {
           $data=$request->all();
           $customer = $this->customer->skipPresenter()->find($request->get('customer_id'));
           $brands       = (isset($data['brands']) ? $data['brands'] : []);

            foreach ($brands as $brand_id) {
                    $customer->setNotificationBrand($brand_id);
            }

            $brands=$customer->notificationBrand;

            return response()->json([
                    'status'=>true,
                    'code'=>config('responses.success.status_code'),
                    'data'=> $brands,
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


      public function indexBrand(CustomerNotificationBrandsRequest $request){

      try
        {
           $data=$request->all();
           $customer = $this->customer->skipPresenter()->find($request->get('customer_id'));
            $brands=$customer->notificationBrand;
            return response()->json([
                    'status'=>true,
                    'code'=>config('responses.success.status_code'),
                    'data'=> $brands,
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


    public function createBrandAssociate(CustomerNotificationBrandAssociateRequest $request){

      try
        {
           $data=$request->all();
           $customer = $this->customer->skipPresenter()->find($request->get('customer_id'));
           $salesreps              = (isset($data['salesreps']) ? $data['salesreps'] : []);

            foreach ($salesreps as $salesrep_id) {

                $customer->setNotificationBrandAssociates($salesrep_id);
            }

            $salesreps=$customer->notificationBrandAssociates;

            return response()->json([
                    'status'=>true,
                    'code'=>config('responses.success.status_code'),
                    'data'=> $salesreps,
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


      public function indexBrandAssociate(CustomerNotificationBrandsRequest $request){

      try
        {

           $data=$request->all();

           $customer = $this->customer->skipPresenter()->find($request->get('customer_id'));
            $salesreps=$customer->notificationBrandAssociates;
            return response()->json([
                    'status'=>true,
                    'code'=>config('responses.success.status_code'),
                    'data'=> $salesreps,
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
     * Delete
     *
     *  Delete an existing Brand Associate.
     *
     * @param      \App\Http\Requests\Api\CustomerDeleteRequest  $request  The request
     *
     * @return     Response
     */
    public function destroyBrandAssociate(CustomerNotificationBrandAssociateRequest $request)
    {
        try{
               $customer                  = $this->customer->skipPresenter()->find($request->get('customer_id'));
               $salesrep_ids              = (isset($data['salesrep_ids']) ? $data['salesrep_ids'] : []);
               $customer->pivotNotificationBrandAssociates()->whereIn('salesrep_id', $salesrep_ids)->delete();

           return response()->json([
                    'status'=>true,
                    'code'=>config('responses.success.status_code'),
                    'data'=> 'Deleted succesfully',
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
}