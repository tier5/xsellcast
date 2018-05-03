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

use App\Http\Requests\Api\RequestAptShowRequest;
use App\Http\Requests\Api\CTARequestPostRequest;
use App\Storage\CustomerRequest\CustomerRequest;
use App\Storage\Offer\OfferRepository;
use Snowfire\Beautymail\Beautymail;
use App\Storage\LbtWp\WpConvetor;


/**
 * @resource Request Appointment
 *
 * Request Appointment resource.
 */
class CTARequestController extends Controller
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
		$this->offer 			= $offer;
	}

	/**
	 * All
	 *
	 * Get a list of all appointment requests by a specific customer.
	 *
	 * @param      \App\Http\Requests\Api\DirectMessageIndexRequest  $request  The request
	 *
	 * @return     Response
	 */
	public function index(RequestTypeAllIndexRequest $request)
	{
		try{

		$user     = $this->customer->skipPresenter()->find($request->get('customer_id'))->user;

		$messages = $this->message->sentByUser($user->id)->queryApptMessage()->orderBy('created_at', 'desc')->paginate(20);

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
	 * Get an appointment request of a specific customer.
	 *
	 * @param      \App\Http\Requests\Api\RequestPriceIndexRequest  $request  The request
	 *
	 * @return     Response
	 */
	public function show(RequestAptShowRequest $request)
	{
		try{

		$user    = $this->customer->skipPresenter()->find($request->get('customer_id'))->user;

		$message = $this->message->sentByUser($user->id)->queryApptMessage()->find($request->get('message_id'));

    	$data=[
				'status'=>true,
                'code'=>config('responses.success.status_code'),
                'message'=>config('responses.success.status_message'),
                ];

        $data=array_merge($data,$message);

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
	 * Create
	 *
	 * Create (send) an appointment request to Sales Rep from a specific customer.
	 *
	 * @param      \App\Http\Requests\Api\DirectMessageStoreRequest  $request  The request
	 *
	 * @return     Response
	 */
	public function store(CTARequestPostRequest $request)
	{

		try{

			$wp_customer_id=$request->get('wp_customer_id');
            $wp=new WpConvetor();
            $customer_id=$wp->getId('customer',$wp_customer_id);
			$customer = $this->customer->skipPresenter()->find($customer_id);

			$wp_offer_id=$request->get('wp_offer_id');
			$offer_id=$wp->getId('offer',$wp_offer_id);
			$offer    = $this->offer->skipPresenter()->find($offer_id);

			$body     = $request->get('body');
			$type_id=$request->get('type');
			$phoneNumber = $request->get('phone_number');
			// $type 	  = $this->getType($request->get('type'));
			$data=[
				'customer'		=> $customer,
			 	'offer'			=> $offer,
			 	// 'type'		=> $type,
			 	'body'			=> $body,
			 	'phoneNumber'	=> $phoneNumber
			];
			switch ($type_id) {
				case 1:
					$thread= $this->storeAppt($data);
					break;
				case 2:
					$thread= $this->storeInfo($data);
					break;
				case 3:
					$thread= $this->storePrice($data);
					break;
				case 4:

					$thread= $this->storeContact($data);
					break;
				case 5:
					$thread =$this->storeDirectMessage($data);
					break;
				default:
				$thread=null;
				break;
			}

			 if($thread!=null){
				return response()->json([
	                    'status'=>true,
	                    'code'=>config('responses.success.status_code'),
	                    'data'=>$thread,
	                    'message'=>config('responses.success.status_message'),
	                ], config('responses.success.status_code'));

			 } else{
				return response()->json([
					'status'=>false,
	                'code'=>config('responses.bad_request.status_code'),
	                'data'=>null,
	                'message'=>'Something went wrong'
				]);
			}

		}catch(\Exception $e){

			return response()->json([
				'status'=>false,
                'code'=>config('responses.bad_request.status_code'),
                'data'=>null,
                'message'=>$e->getMessage()
			]);

		}
	}

	// public function getType($type_id){
	// 	$type=[];
	// 	// 1=appointment, 2=info, 3=price, 4=contact, 5=Direct message
	// 	switch ($type_id) {
	// 		case 1:
	// 			$type=['name'=>'appt','label'=>'Appointment','email_template'=>'ba-addappt'];
	// 			break;
	// 		case 2:
	// 		$type=['name'=>'info','label'=>'Information','email_template'=>'ba-addinfo'];
	// 		break;
	// 		case 3:
	// 		$type=['name'=>'price','label'=>'Price','email_template'=>''];
	// 		break;
	// 		case 4:
	// 		$type=['name'=>'contact','label'=>'Contact','email_template'=>''];
	// 		break;
	// 		case 5:
	// 		$type=['name'=>'direct_message','label'=>'Direct message','email_template'=>''];
	// 		break;
	// 		default:
	// 		$type=['name'=>'','label'=>'','email_template'=>''];
	// 		break;
	// 	}

	// 	return $type;
	// }

	public function storeAppt($data){

		$customer=$data['customer'];
		$offer=$data['offer'];
		$body=$data['body'];

		$thread	  = $this->customer_request->sendRequest($customer, $offer, 'appt', $body);

			//send mail to BA
        $ba=$this->customer->findNereastBAOfOffer($offer,$customer);

		if($ba){
            $beautymail = app()->make(Beautymail::class);
            $beautymail->send('emails.api.ba-addappt', compact('ba','offer','customer'), function($message) use($ba)
            {
                $message
                    ->from(env('NO_REPLY'))
                    // ->from(env('MAIL_USERNAME'))
                    ->to($ba->user->email, $ba->user->firstname . ' ' . $ba->user->lastname)
                    ->subject('New Appointment');
            });

            //send mail to prospect
            $prospectMail = app()->make(Beautymail::class);

            $prospectMail->send('emails.api.prospect-newappt', compact('ba','offer','customer'), function($message) use($customer)
            {
                $message
                    ->from(env('NO_REPLY'))
                    // ->from(env('MAIL_USERNAME'))
                    ->to($customer->user->email, $customer->user->firstname . ' ' . $customer->user->lastname)
                    ->subject('New Appointment');
            });
		}

		return $thread;
	}

	public function storeInfo($data){

		$customer=$data['customer'];
		$offer=$data['offer'];
		$body=$data['body'];

		$thread   = $this->customer_request->sendRequest($customer, $offer, 'info', $body);

		//send mail to BA
        $ba=$this->customer->findNereastBAOfOffer($offer,$customer);
		if($ba){

            $beautymail = app()->make(Beautymail::class);
            $beautymail->send('emails.api.ba-addinfo', compact('ba','offer','customer'), function($message) use($ba)
            {
                $message
                    ->from(env('NO_REPLY'))
                    // ->from(env('MAIL_USERNAME'))
                    ->to($ba->user->email, $ba->user->firstname . ' ' . $ba->user->lastname)
                    ->subject('New Information Request');
            });
            //send mail to prospect
            $prospectMail = app()->make(Beautymail::class);
            $prospectMail->send('emails.api.prospect-newappt', compact('ba','offer','customer'), function($message) use($customer)
            {
                $message
                    ->from(env('NO_REPLY'))
                    // ->from(env('MAIL_USERNAME'))
                    ->to($customer->user->email, $customer->user->firstname . ' ' . $customer->user->lastname)
                    ->subject('Information Request');
            });
		}

		return $thread;
	}
	public function storePrice($data){

		$customer=$data['customer'];
		$offer=$data['offer'];
		$body=$data['body'];

 		$thread = $this->customer_request->sendRequest($customer, $offer, 'price', $body);

		return $thread;
	}
	public function storeContact($data){

		$customer=$data['customer'];
		$offer=$data['offer'];
		$body=$data['body'];
		$phoneNumber=$data['phoneNumber'];

		$thread      = $this->customer_request->sendContactRequest($customer, $offer, $body, $phoneNumber);

		return $thread;
	}

	public function storeDirectMessage($data){

		$customer=$data['customer'];
		$offer=$data['offer'];

		$body=$data['body'];
		$subject  = str_limit($body, 30, '');

		$phoneNumber=$data['phoneNumber'];

		// $salesrep_id=$data['salesrep_id'];
		// $salesrep = $this->salesrep->skipPresenter()->find($salesrep_id);
		$salesrep=$this->customer->findNereastBAOfOffer($offer,$customer);
		// dd($salesrep);
		if($salesrep){
			$pivot    = $salesrep->customersPivot()->where('customer_id', $customer->id)->first();
			$thread   = $this->thread->createMessage($customer->user->id, $salesrep->user->id, $body, 'message', $subject);
			return $thread;
		}else {
			return false;
		}

	}
}
