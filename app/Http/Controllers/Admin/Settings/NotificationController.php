<?php namespace App\Http\Controllers\Admin\Settings;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Crud\Crud;
use Auth;

class NotificationController extends Controller
{
	protected $crud;

	public function __construct()
	{
		$this->crud = new Crud();	
	}

	public function index()
	{
		$user          = Auth::user();
		$layoutColumns = $this->crud->layoutColumn();
		$form          = 'App\Storage\User\ProfileCrud@notificationsForm';

    	$layoutColumns->addItemForm($form, ['view_args' => ['user' => $user]]);

		return $this->crud->pageView($layoutColumns);		
	}

	public function save(Request $request)
	{
		$notify                = (boolean)$request->get('email_notify');
		$user                  = Auth::user();
		$user->is_email_notify = $notify;

		$user->save();
		$request->session()->flash('message', 'Notification has been updated!');

		return redirect()->route('admin.settings.notifications');	
	}
}