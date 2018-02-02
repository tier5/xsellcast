<?php namespace App\Http\Controllers\Admin\Settings;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Crud\Crud;
use Auth;
use App\Http\Requests\Admin\SettingsProfileSaveRequest;
use App\Storage\Dealer\DealerRepository;
use App\Storage\Messenger\Thread;
use App\Storage\Messenger\ThreadRepository;

class SalesRepProfileController extends Controller
{
	protected $thread;

	public function __construct(ThreadRepository $thread)
	{
		$this->thread = $thread;
	}

	public function acceptAgreement(Request $request)
	{
		$user = Auth::user();
		$this->thread->createSystemMessage($user->id, 'messages.system.ba-welcome', 'Welcome!');

		$user->salesRep->setTrueAgreement();
		$request->session()->flash('message', 'Profile updated!');

		return redirect(route('admin.welcome.salesrep'));
	}

}
