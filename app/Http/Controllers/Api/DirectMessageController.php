<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Messenger\MessageRepository;
use App\Storage\Messenger\ThreadRepository;
use App\Storage\Customer\CustomerRepository;
use App\Storage\SalesRep\SalesRepRepository;
use App\Http\Requests\Api\DirectMessageIndexRequest;
use App\Http\Requests\Api\DirectMessageShowRequest;
use App\Http\Requests\Api\DirectMessageStoreRequest;
use App\Http\Requests\Api\DirectMessageMarkReadPostRequest;
use App\Storage\Offer\OfferRepository;

/**
 * @resource Direct Message
 *
 * Messages resource.
 */
class DirectMessageController extends Controller
{
	protected $message;

	protected $customer;

	protected $salesrep;

	protected $thread;

	protected $offer;

	public function __construct(MessageRepository $message, CustomerRepository $customer, SalesRepRepository $salesrep, ThreadRepository $thread, OfferRepository $offer)
	{
		$this->message  = $message;
		$this->customer = $customer;
		$this->salesrep = $salesrep;
		$this->thread 	= $thread;
		$this->offer 	= $offer;
	}

	/**
	 * All
	 *
	 * Get a list of all direct messages for a specific customer.
	 *
	 * @param      \App\Http\Requests\Api\DirectMessageIndexRequest  $request  The request
	 *
	 * @return     Response
	 */
	public function index(DirectMessageIndexRequest $request)
	{
		$user     = $this->customer->skipPresenter()->find($request->get('customer_id'))->user;
		$messages = $this->message->receivedByUser($user->id)->queryDirectMessage()->orderBy('created_at', 'desc')->paginate(20);

    	return response()->json($messages);
	}

	/**
	 * Sent
	 * 
	 * Get list of direct messages sent by a customer.
	 *
	 * @param      \App\Http\Requests\Api\DirectMessageIndexRequest  $request  The request
	 *
	 * @return     Response
	 */
	public function sent(DirectMessageIndexRequest $request)
	{
		$user     = $this->customer->skipPresenter()->find($request->get('customer_id'))->user;
		$messages = $this->message->sentByUser($user->id)->queryDirectMessage()->orderBy('created_at', 'desc')->paginate(20);

    	return response()->json($messages);
	}

	/**
	 * Single
	 *
	 * Get a message of a specific customer.
	 *
	 * @param      \App\Http\Requests\Api\DirectMessageShowRequest  $request  The request
	 *
	 * @return     Response
	 */
	public function show(DirectMessageShowRequest $request)
	{
		$user    = $this->customer->skipPresenter()->find($request->get('customer_id'))->user;
		$message = $this->message->receivedByUser($user->id)->queryDirectMessage()->find($request->get('message_id'));

    	return response()->json($message);
	}

	/**
	 * Mark as Read
	 * 
	 * Mark a message as read.
	 *
	 * @param      \App\Http\Requests\Api\DirectMessageMarkReadPostRequest  $request  The request
	 *
	 * @return     <type>                                                   ( description_of_the_return_value )
	 */
	public function markAsRead(DirectMessageMarkReadPostRequest $request)
	{
		$user     = $this->customer->skipPresenter()->find($request->get('customer_id'))->user;
		$message  = $this->message->receivedByUser($user->id)->queryDirectMessage()->skipPresenter()->find($request->get('message_id'));
		$markRead = $request->get('mark_read');

		$message->thread->markAsRead($user->id);

    	return response()->json($this->message->skipPresenter(false)->find($message->id));		
	}

	/**
	 * Create
	 *
	 * Create (send) a message to Sales Rep from a specific customer.
	 *
	 * @param      \App\Http\Requests\Api\DirectMessageStoreRequest  $request  The request
	 *
	 * @return     Response
	 */
	public function store(DirectMessageStoreRequest $request)
	{
		$customer = $this->customer->skipPresenter()->find($request->get('customer_id'));
		$salesrep = $this->salesrep->skipPresenter()->find($request->get('salesrep_id'));
		$body     = $request->get('body');
		$subject  = str_limit($body, 30, '');
		$pivot    = $salesrep->customersPivot()->where('customer_id', $customer->id)->first();
		$thread   = $this->thread->createMessage($customer->user->id, $salesrep->user->id, $body, 'message', $subject);

		return response()->json($thread);
	}
}
