<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Customer\CustomerRepository;
use App\Storage\NotificationSetting\NotificationSettingRepository;
use App\Storage\SalesRep\SalesRepRepository;
// use App\Storage\Customer\Customer;
// use App\Storage\User\User;


use App\Http\Requests\Api\CustomerNotificationNewsRequest;

/**
 * @resource Customer Notification Setting
 *
 * Customer Notification resource.
 */
class CustomerNotificationSettingController extends Controller
{
	protected $notification;

	protected $customer;

    protected $salesrep;

	public function __construct(NotificationSettingRepository $notification, CustomerRepository $customer, SalesRepRepository $salesrep)
	{
        $this->notification = $notification;
        $this->customer = $customer;
        $this->salesrep = $salesrep;

	}
	public function createNews(CustomerNotificationNewsRequest $request){

	  try
        {
           $data=$request->all();
			$news=$this->notification->createNews($data);
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

}