<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Messenger\MessageRepository;
use App\Storage\Messenger\ThreadRepository;
use App\Storage\Customer\CustomerRepository;
use App\Storage\SalesRep\SalesRepRepository;
use App\Http\Requests\Api\RequestTypeAllIndexRequest;
use App\Http\Requests\Api\RequestTypeAllShowRequest;
use App\Http\Requests\Api\RequestTypeAllStoreRequest;
use App\Http\Requests\Api\RequestInfoShowRequest;
use App\Storage\CustomerRequest\CustomerRequest;
use App\Storage\Offer\OfferRepository;
use Snowfire\Beautymail\Beautymail;

/**
 * @resource Request Info
 *
 * Request Info resource.
 */
class RequestInfoController extends Controller
{
	protected $message;

	protected $customer;

	protected $salesrep;

	protected $thread;

	protected $customer_request;

	protected $offer;

	public function __construct(MessageRepository $message, CustomerRepository $customer, SalesRepRepository $salesrep, ThreadRepository $thread, OfferRepository $offer)
	{
		$this->message          = $message;
		$this->customer         = $customer;
		$this->salesrep         = $salesrep;
		$this->thread           = $thread;
		$this->customer_request = new CustomerRequest();
		$this->offer = $offer;
	}

	/**
	 * All
	 *
	 * Get a list of all info requests by a specific customer.
	 *
	 * @param      \App\Http\Requests\Api\DirectMessageIndexRequest  $request  The request
	 *
	 * @return     Response
	 */
	public function index(RequestTypeAllIndexRequest $request)
	{
		try{
		$user     = $this->customer->skipPresenter()->find($request->get('customer_id'))->user;


		$messages = $this->message->sentByUser($user->id)->queryInfoMessage()->orderBy('created_at', 'desc')->paginate(20);

		$data=[
				'status'=>true,
                'code'=>config('responses.success.status_code'),
                'message'=>config('responses.success.status_message'),
                ];

        $data=array_merge($data,$messages);

    	return response()->json($data, config('responses.success.status_code'));

    	}catch(\Exception $e){

    		return response()->json([
				'status'=>false,
                'code'=>config('responses.bad_request.status_code'),
                'data'=>null,
                'message'=>$e->getMessage()
			]);
    	}
    }

	/**
	 * Single
	 *
	 * Get an info request of a specific customer.
	 *
	 * @param      \App\Http\Requests\Api\RequestPriceIndexRequest  $request  The request
	 *
	 * @return     Response
	 */
	public function show(RequestInfoShowRequest $request)
	{
		try{


			$user    = $this->customer->skipPresenter()->find($request->get('customer_id'))->user;

			$message = $this->message->sentByUser($user->id)->queryInfoMessage()->find($request->get('message_id'));

    		return response()->json([
	                    'status'=>true,
	                    'code'=>config('responses.success.status_code'),
	                    'data'=>$message,
	                    'message'=>config('responses.success.status_message'),
	                ], config('responses.success.status_code'));

		}catch(\Exception $e){

			return response()->json([
				'status'=>false,
                'code'=>config('responses.bad_request.status_code'),
                'data'=>null,
                'message'=>$e->getMessage()
			]);

		}
	}

	/**
	 * Create
	 *
	 * Create (send) an info request to Sales Rep from a specific customer.
	 *
	 * @param      \App\Http\Requests\Api\DirectMessageStoreRequest  $request  The request
	 *
	 * @return     Response
	 */
	public function store(RequestTypeAllStoreRequest $request)
	{
		try{

			$customer = $this->customer->skipPresenter()->find($request->get('customer_id'));

			$offer    = $this->offer->skipPresenter()->find($request->get('offer_id'));

			$body     = $request->get('body');

			$thread   = $this->customer_request->sendRequest($customer, $offer, 'info', $body);

			//send mail to BA
            // $ba=$this->customer->findNereastBAOfOffer($offer,$customer);

            // $beautymail = app()->make(Beautymail::class);
            // $beautymail->send('emails.api.ba-addinfo', compact('ba','offer','customer'), function($message) use($ba)
            // {
            //     $message
            //         ->from(env('NO_REPLY'))
            //         // ->from(env('MAIL_USERNAME'))
            //         ->to($ba->user->email, $ba->user->firstname . ' ' . $ba->user->lastname)
            //         ->subject('New Information Request');
            // });
            // //send mail to prospect
            // $prospectMail = app()->make(Beautymail::class);
            // $prospectMail->send('emails.api.prospect-newappt', compact('ba','offer','customer'), function($message) use($customer)
            // {
            //     $message
            //         ->from(env('NO_REPLY'))
            //         // ->from(env('MAIL_USERNAME'))
            //         ->to($customer->user->email, $customer->user->firstname . ' ' . $customer->user->lastname)
            //         ->subject('Information Request');
            // });

		 	return response()->json([
	                    'status'=>true,
	                    'code'=>config('responses.success.status_code'),
	                    'data'=>$thread,
	                    'message'=>config('responses.success.status_message'),
	                ], config('responses.success.status_code'));

		}catch(\Exception $e){

			return response()->json([
				'status'=>false,
                'code'=>config('responses.bad_request.status_code'),
                'data'=>null,
                'message'=>$e->getMessage()
			]);

		}
	}
}
