<?php namespace App\Http\Controllers\Admin\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Crud\Crud;
use Auth;
use App\Storage\UserAction\UserActionRepository;

class CustomerActivityController extends Controller
{
    
	protected $crud;

	protected $user_action;

	public function __construct(UserActionRepository $user_action)
	{
		$this->user_action = $user_action;
	}


	public function index()
	{
		$user          = Auth::user();
		$salesRep      = $user->salesRep;		
		$customerActivities = $this->user_action->getSalesRepCustomerActivities($salesRep)
			->paginate(20);

		return response()->json($customerActivities);
	}
}