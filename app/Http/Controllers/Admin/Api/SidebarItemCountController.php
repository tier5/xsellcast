<?php namespace App\Http\Controllers\Admin\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Messenger\Message;
use App\Storage\Customer\CustomerRepository;
use App\Storage\Messenger\MessageRepository;
use App\Storage\Messenger\ThreadRepository;

class SidebarItemCountController extends Controller
{
	protected $customer;

	protected $message;

	protected $thread;

	protected $message_types = ['message', 'appt', 'price', 'info', 'lead_reassign', 'system', 'contact_me'];

	public function __construct(CustomerRepository $customer, MessageRepository $message, ThreadRepository $thread)
	{
		$this->customer = $customer;
		$this->message  = $message;
		$this->thread   = $thread;
	}

	public function messsageAll(Request $request)
	{
		$user   = $request->user();
		$counts = 0;

        foreach($this->message_types as $k){
			$unreadCount = $this->message->listWithApprovedAll($user, $k)->count();
			$counts      += $unreadCount;

        }

        return response()->json([ 'count' =>  $counts]);
	}

	public function messsageAllOver(Request $request)
	{
		$user    = $request->user();
		$counts  = array('all' => 0, 'unread' => 0);
		$message = $this->message;

        foreach($this->message_types as $k){
			$message          = $this->message;
			$unreadCount      = $message->listWithApprovedUnread($user, $k)->count(); 
			$allCount         =	$message->listWithApprovedAll($user, $k)->count();
			$counts['unread'] += $unreadCount;
			$counts['all']    += $allCount;
        }

        $allMsgCount = ($counts['unread'] > 0 ? $counts['unread'] . '/' . $counts['all'] : 0);

        return response()->json([ 'count' =>  $allMsgCount]);
	}

	public function messsageAppt(Request $request)
	{
		$user  = $request->user();
		$k     = 'appt';
		$count = $this->message->listWithApprovedUnread($user, $k)->count();

        return response()->json([ 'count' =>  $count]);
	}	

	public function messsageContactMe(Request $request)
	{
		$user  = $request->user();
		$k     = 'contact_me';
		$count = $this->message->listWithApprovedUnread($user, $k)->count();

        return response()->json([ 'count' =>  $count]);
	}		

	public function messsageDirect(Request $request)
	{
		$user  = $request->user();
		$k     = 'message';
		$count = $this->message->listWithApprovedUnread($user, $k)->count();

        return response()->json([ 'count' =>  $count]);
	}		

	public function messsagePrice(Request $request)
	{
		$user  = $request->user();
		$k     = 'price';
		$count = $this->message->listWithApprovedUnread($user, $k)->count();

        return response()->json([ 'count' =>  $count]);
	}		

	public function messsageInfo(Request $request)
	{
		$user  = $request->user();
		$k     = 'info';
		$count = $this->message->listWithApprovedUnread($user, $k)->count();

        return response()->json([ 'count' =>  $count]);
	}		

	public function prospect(Request $request)
	{
		$user = $request->user();
		$prospectCount = 0;

		if(!$user->hasRole('sales-rep')){

			$prospectCount = $this->customer->skipPresenter()->noAssignedSalesrep()->all()->count();
		}else{

			$prospectCount = $this->thread->newLeadsListCount($user);
			$newLeadCount  = $this->thread->newLeadsListUnreadCount($user);

			if($newLeadCount > 0)
			{
				$prospectCount = $newLeadCount . '/' . $prospectCount;
			}else{
				$prospectCount = 0;
			}
		}

		return response()->json([ 'count' =>  $prospectCount]);
	}

	public function newProspect(Request $request)
	{
		$user   = $request->user();
		$count  = $this->thread->newLeadsListUnreadCount($user);

		return response()->json([ 'count' =>  $count]);
	}

	public function newProspectDreprecated(Request $request)
	{
		$user          = $request->user();
		$prospectCount = 0;

		if($user->hasRole('sales-rep')){

			$prospectCount = $user->salesRep()->first()->pendingCustomers()->count();
		}

		return response()->json([ 'count' =>  $prospectCount]);		
	}
}

?>