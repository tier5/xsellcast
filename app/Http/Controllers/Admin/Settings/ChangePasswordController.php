<?php namespace App\Http\Controllers\Admin\Settings;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Crud\Crud;
use Auth;
use App\Http\Requests\Admin\ChangePasswordSaveRequest;
use Hash;
use Snowfire\Beautymail\Beautymail;

class ChangePasswordController extends Controller
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
		$form          = 'App\Storage\User\ProfileCrud@changePass';

		if($user->isFbUserNotPasswordSet())
		{
			$form = 'App\Storage\User\ProfileCrud@fbUserSetPass';
		}

		if($user->isFbUserNoEmail())
		{
			$layoutColumns->addItem('admin.settings.fb-no-email');
		}else{
			$layoutColumns->addItemForm($form, ['view_args' => ['user' => $user]]);
		}

		return $this->crud->pageView($layoutColumns);		
	}

	public function save(ChangePasswordSaveRequest $request)
	{
		$user        = $request->get('user');
		$isSalesrep  = $user->hasRole('sales-rep');
		$newHashPass = Hash::make($request->get('new_password'));

		if($newHashPass == $user->password && !$user->isFbUserNotPasswordSet())
		{
			$request->session()->flash('error', 'Invalid password. Password must be different from current password.');
			return redirect()->route('admin.settings.change.password');	  
		}

		$isUnconfirmPass = $user->salesRep->password_changed;

        $user->fill([
            'password' => $newHashPass
        ])->save();		

        if($isSalesrep){

        	$user->salesRep->setToPasswordChanged();
        }

        if(!$isUnconfirmPass)
        {
        	$request->session()->flash('show_salesrep_agree_modal', true);
        	$route = 'admin.settings.profile';
        }else
        {
        	if($user->isFbUserNotPasswordSet())
        	{
        		/**
        		 * Flash message for FB user who not yet setting password.
        		 */
        		$request->session()->flash('message', 'Your password was successfully set.');
        	}else{
        		$request->session()->flash('message', 'Your password was successfully changed.');
        	}
        	
        	$route = 'admin.settings.change.password';
        }

        if($user->isFbUserNotPasswordSet())
        {
        	/**
        	 * Set this for FB user.
        	 */
        	$user->setMeta('fb_set_password', true);
        	$user->save();
        }        
 
        $beautymail = app()->make(Beautymail::class);
        $beautymail->send('emails.auth.change-password', compact('user'), function($message) use($user)
        {

            $message
                ->from('admin@xsellcast.com')
                ->to($user->email, $user->firstname . ' ' . $user->lastname)
                ->subject('Your password was succesfully changed');
        });

		return redirect()->route($route);      
	}
}