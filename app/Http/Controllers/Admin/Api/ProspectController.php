<?php namespace App\Http\Controllers\Admin\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Crud\Crud;
use App\Storage\Customer\CustomerRepository;
use Auth;
use App\Storage\CustomerRequest\CustomerRequest;
use App\Storage\SalesRep\SalesRep;
use App\Storage\Customer\CustomerSalesRep;
use App\Storage\User\User;
use App\Storage\Messenger\Thread;
use App\Storage\Messenger\ThreadRepository;
use App\Storage\Offer\Offer;

class ProspectController extends Controller
{
	protected $customer;

	protected $custemer_request;

	protected $thread;

	public function __construct(CustomerRepository $customer, ThreadRepository $thread)
	{
		$this->customer = $customer;
		$this->customer_request = new CustomerRequest();	
		$this->thread = $thread;	
	}

	public function nameEmail(Request $request)
	{
		$search    = $request->get('term');
		$customers = $this->customer->setPresenter('App\Storage\Customer\CustomerAutoCompletePresenter')->whereHas('user', function($q) use($search){

			$q->where('firstname', 'like', '%' . $search . '%')
				->orWhere('lastname', 'like', '%' . $search . '%')
				->orWhere('email', 'like', '%' . $search . '%');
		})->all();

		return response()->json($customers['data'])->setCallback($request->get('callback'));
	}

	public function setProspectToSalesrep(Request $request)
	{
		$salesrepId        = $request->get('salesrep_id');
		$customerId        = $request->get('customer_id');
		$customer          = $this->customer->skipPresenter()->find($customerId);
		$salesrep          = SalesRep::find($salesrepId);
		$brand             = $salesrep->dealers->first()->brands->first();
		$dealerSalesRepIds = [];

		foreach ($salesrep->dealers->first()->brands->first()->dealers as $d) {
			foreach($d->salesReps as $s)
			{
			//	$dealerSalesRepIds[] = $s->id;
			}
		}

		$salesrepIds = $brand->salesReps()->lists('id')->toArray();
		$salesrepUsers = User::forSalesRepsIn($salesrepIds)->get(); //($dealerSalesRepIds)->get();
		$salesrepUserIds = $salesrepUsers->lists('id');

		/**
		 * Get lists of thread that is from customer which is associated with BAs.
		 *
		 * @var  Thread
		 */
		$threads = $this->thread
			->requestUnApprForCustUserAndInUser($customer->user->id, $salesrepUserIds)
			->skipPresenter()
			->all();

		/**
		 * All request thread that is not yet approved assing to new BA.
		 */
		foreach ($threads as $thread) {

			foreach($salesrepUserIds as $id){
				$thread->removeParticipant($id);
			}
			$thread->addParticipant($salesrep->user->id);
		}

		/**
		 * Send re-assignment notification to previous salesrep.
		 */
		foreach($salesrepUserIds as $id)
		{
			if($salesrep->user->id != $id){
				/**
				 * has assign to other message
				 */
				$this->thread->sendSalesRepLeadReassign($customer->user->id, $id);

				/**
				 * Reassignment message to new assigned BA.
				 */
				$this->thread->sendSalesRepNewLeadReassign($customer->user->id, $salesrep->user->id);
			}
		}

		//$dealerSalesRepIds = $salesrep->dealers->first()->salesReps->lists('id')->toArray();
		$custReps          = CustomerSalesRep::where('customer_id', $customerId)->whereIn('salesrep_id', $salesrepIds)->get();

		foreach($custReps as $r)
		{
			$r->delete();
		}

		$cs = CustomerSalesRep::create([
			'customer_id' => $customerId,
			'salesrep_id' => $salesrepId]);

		$this->thread->newLeadNotification($customer->user->id, $salesrep->user->id);

		$customer->updatedAtNow();

		return response()->json();
	}

	public function acceptLead(Request $request)
	{
		$offerId        = $request->get('offer_id');
		$offer          = Offer::find($offerId);
		$customerUserId = $request->get('customer_user_id');
		$salesrepUserId = $request->get('salesrep_user_id');
		$salesrepUser   = User::find($salesrepUserId);
		$customerUser   = User::find($customerUserId);
		$brand          = $salesrepUser->salesRep->dealers->first()->brands->first();
		$salesrepId     = $salesrepUser->salesRep->id;
		$salesrepIds    = $brand->salesReps()->lists('id')->toArray();
		$custSalesrep   = CustomerSalesRep::whereIn('salesrep_id', $salesrepIds)->get();
	
		$customerUser->customer->updatedAtNow();

		foreach($custSalesrep as $row)
		{
			if($row->salesrep_id == $salesrepId)
			{
				$row->approved    = true;
				$row->rejected    = false;
				$row->rejected_at = null;
				$row->save();
			}else{
				$row->delete();
			}
		}

		$request->session()->flash('message', 'Lead has been approved!');
		return response()->json();
	}

	public function rejectLead(Request $request)
	{

		$offerId        = $request->get('offer_id');
		$customerUserId = $request->get('customer_user_id');
		$salesrepUserId = $request->get('salesrep_user_id');
		$threadId       = $request->get('thread_id');
		$salesrepUser   = User::find($salesrepUserId);
		$customerUser   = User::find($customerUserId);
		$custSalesrep   = CustomerSalesRep::where('salesrep_id', $salesrepUser->salesRep->id)->where('customer_id', $customerUser->customer->id)->get();

		$customerUser->customer->updatedAtNow();

		foreach($custSalesrep as $row)
		{
			$row->approved = false;
			$row->rejected = true;
			$row->rejected_at = \Carbon\Carbon::now();
			$row->save();
		}

		$request->session()->flash('message', 'Lead has been rejected!');
		return response()->json();		
	}
}

?>