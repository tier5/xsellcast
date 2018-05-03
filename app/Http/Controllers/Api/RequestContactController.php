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
use App\Http\Requests\Api\RequestTypeContactStoreRequest;
use App\Storage\CustomerRequest\CustomerRequest;
use App\Storage\Offer\OfferRepository;

/**
 * @resource Request Contact
 *
 * Request Contact resource.
 */
class RequestContactController extends Controller
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
	 * Get a list of all contact requests by a specific customer.
	 *
	 * @param      \App\Http\Requests\Api\DirectMessageIndexRequest  $request  The request
	 *
	 * @return     Response
	 */
	public function index(RequestTypeAllIndexRequest $request)
	{
		$user     = $this->customer->skipPresenter()->find($request->get('customer_id'))->user;
		$messages = $this->message->sentByUser($user->id)->queryApptMessage()->orderBy('created_at', 'desc')->paginate(20);

    	return response()->json($messages);
	}

	/**
	 * Single
	 *
	 * Get an contact request of a specific customer.
	 *
	 * @param      \App\Http\Requests\Api\RequestPriceIndexRequest  $request  The request
	 *
	 * @return     Response
	 */
	public function show(RequestTypeAllShowRequest $request)
	{
		$user    = $this->customer->skipPresenter()->find($request->get('customer_id'))->user;
		$message = $this->message->sentByUser($user->id)->queryApptMessage()->find($request->get('message_id'));

    	return response()->json($message);
	}

	/**
	 * Create
	 *
	 * Create (send) an contact request to Sales Rep from a specific customer.
	 *
	 * @param      \App\Http\Requests\Api\DirectMessageStoreRequest  $request  The request
	 *
	 * @return     Response
	 */
	public function store(RequestTypeContactStoreRequest $request)
	{
		$customer    = $this->customer->skipPresenter()->find($request->get('customer_id'));
		$offer       = $this->offer->skipPresenter()->find($request->get('offer_id'));
		$body        = $request->get('body');
		$phoneNumber = $request->get('phone_number');

		$thread      = $this->customer_request->sendContactRequest($customer, $offer, $body, $phoneNumber);

		return response()->json($thread);
	}
}
