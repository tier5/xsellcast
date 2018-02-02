<?php namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Crud\Crud;
use App\Http\Requests\Admin\WelcomeSalesRepIndexRequest;

class WelcomeSalesRepController extends Controller
{
	protected $crud;

	public function __construct()
	{
		$this->crud = new Crud();
	}

	public function index(WelcomeSalesRepIndexRequest $request)
	{
		$user          = $request->get('user');
		$layoutColumns = $this->crud->layoutColumn();

    	$layoutColumns->addItem('admin.welcome.salesrep', ['view_args' => compact('user')]);

		return $this->crud->pageView($layoutColumns);  
	}

}