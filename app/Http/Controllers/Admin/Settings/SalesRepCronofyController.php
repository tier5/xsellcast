<?php namespace App\Http\Controllers\Admin\Settings;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Crud\Crud;
use Auth;
// use App\Http\Requests\Admin\SettingsProfileSaveRequest;
// use App\Storage\Dealer\SalesRepRepository;
// use App\Storage\Messenger\Thread;
use App\Storage\SalesRep\SalesRepRepository;

class SalesRepCronofyController extends Controller
{
	protected $salesRep;
	protected $crud;

	public function __construct(SalesRepRepository $salesRep)
	{
		$this->crud = new Crud();

		$this->salesRep = $salesRep;
	}

	public function index(Request $request)
	{
		$user = Auth::user();
		// $user          = $salesrep->user;
		$layoutColumns = $this->crud->layoutColumn();
		$form          = 'App\Storage\SalesRep\SalesRepCrud@cronofySetting';

    	$layoutColumns->addItemForm($form, ['view_args' => ['user' => $user, 'route' => 'admin.salesrep.update']]);

    	$this->crud->setExtra('sidemenu_active', 'settings.salesrep.cronofysettings');

		return $this->crud->pageView($layoutColumns);

	}
	public function update(Request $request)
	{
dd($request);
	}
}
