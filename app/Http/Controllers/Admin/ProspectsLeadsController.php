<?php namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Crud\Crud;
use App\Storage\Messenger\ThreadRepository;
use App\Storage\Messenger\MessageCrud;
use App\Storage\Messenger\MessageRepository;

class ProspectsLeadsController extends Controller
{
	protected $crud;

	protected $thread;

	protected $message;

	public function __construct(ThreadRepository $thread, MessageRepository $message)
	{
		$this->crud = new Crud();
		$this->thread = $thread;
		$this->message = $message;
	}

	public function index(Request $request)
	{
		$user          = \Auth::user();
		$layoutColumns = $this->crud->layoutColumn();
		$urlParam      = [];
		$search        = $request->get('s');
		$headTitle     = 'New Leads';
		$type          = 'new-leads';
		$thread_count  = $this->thread->newLeadsList($user)->skipPresenter()->all()->count();

    	if($search)
    	{
    		$urlParam['s'] = urlencode($search);
    	}

		$url = route('admin.api.messages.new_leads', $urlParam);
		$tbl = MessageCrud::ajaxTable($url);
		
    	$layoutColumns->addItem('admin.messages.actions', 
    		['show_box' => false, 'column_class' => 'm-b-md', 'column_size' => 12]);
		$layoutColumns->addItem('admin.messages.list', 
			['show_box' => false, 'view_args' => compact('thread_count', 'tbl', 'type'), 'column_size' => 12]);

		return $this->crud->pageView($layoutColumns);  		
	}

}