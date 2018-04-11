<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Storage\Crud\Crud;
use Illuminate\Http\Request;
use App\Storage\Messenger\MessageCrud;
use App\Storage\Messenger\MessageRepository;

class HomeController extends Controller
{
    protected $message;

	public function __construct(MessageRepository $message)
    {
    	$this->crud = new Crud();
        $this->message = $message;
    }

    public function index(Request $request)
    {
        $layoutColumns = $this->crud->layoutColumn();

        $user          = $request->user();
        $search        = $request->get('s');
        $thread_count  = $this->message->allMessages($search, $user->id)->skipPresenter()->all()->count();
        $urlParam      = [];
        $showAction    = false;

        if($search)
        {
            $urlParam['s'] = urlencode($search);
        }

        $url = route('admin.api.messages', $urlParam);
        $tbl = MessageCrud::ajaxTable($url);

        if($user->hasRole('csr'))
        {
            $layoutColumns->addItem('admin.home.csr', ['show_box' => false]);
        }else{

            $layoutColumns->addItem('admin.home.ba', ['show_box' => false]);
        }

        $layoutColumns->addItem('admin.messages.list',
            ['show_box' => false, 'view_args' => compact('thread_count', 'tbl', 'showAction'), 'column_size' => 12]);

		return $this->crud->pageView($layoutColumns);
    }
}