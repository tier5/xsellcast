<?php namespace App\Http\Controllers\Admin\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Storage\User\UserRepository;

class ContactsController extends Controller
{
	protected $user;

	public function __construct(UserRepository $user)
	{
		$this->user = $user;
	}

	public function index(Request $request)
	{
		$user       = Auth::user();
		$search     = $request->get('term');
		$presenter  = 'App\Storage\User\UserAutoCompletePresenter';
		$usersQuery = $this->user->setPresenter($presenter);

		if($user->hasRole('csr'))
		{
			$usersQuery->csrContactList($user->id);
		}else{

			/**
			 * TODO: create a salesRepContactList() method on UserRepository
			 */
			$usersQuery->salesRepContactList($user);
		}

		if($search)
		{
			$usersQuery->search($search);
		}

		$users = $usersQuery->all();

		return response()->json($users['data'])->setCallback($request->get('callback'));;
	}
}