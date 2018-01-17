<?php

namespace App\Http\Controllers\Admin\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Messenger\ThreadRepository;
use App\Storage\Messenger\MessageRepository;
use Auth;

class MessageController extends Controller
{
	protected $thread;

	protected $message;

	public function __construct(ThreadRepository $thread, MessageRepository $message)
	{
		$this->thread  = $thread;
		$this->message = $message;
	}

	public function index(Request $request, $type = null)
	{
		$user          = Auth::user();
		$search        = $request->get('s');
		$messages = $this->message->baseGetAll($user, $search, $type)->paginate(20);

    	return response()->json($messages);		
	}

	public function newLeads(Request $request)
	{
		$user        = Auth::user();

		return response()->json($this->thread->newLeadsList($user)->skipPresenter(false)->paginate(20));	
		//$threads->skipPresenter(false)->paginate(20)
	}

	public function sent(Request $request)
	{
		$user     = Auth::user();
		$search   = $request->get('s');
		$messages = $this->message->useIsReadThreadAjaxPresenter()->allMessages($search, $user->id, null, true)->orderBy('created_at', 'desc')->paginate(20);
		
    	return response()->json($messages);		
	}	

	public function draft(Request $request)
	{
		$user    = Auth::user();
		$search  = $request->get('s');

		$messages = $this->message->useIsReadThreadAjaxPresenter()->allMessages($search, $user->id, null, false, true)->orderBy('created_at', 'desc')->paginate(20);

    	return response()->json($messages);		
	}		
}