<?php namespace App\Http\Controllers\Admin\Settings;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Crud\Crud;
use Auth;
use App\Storage\Dealer\DealerRepository;
use App\Http\Requests\Admin\SettingsProfileSaveSalesRepRequest;
use App\Http\Requests\Admin\SettingsProfileSaveCsrRequest;

class ProfileController extends Controller
{
	protected $crud;

	protected $dealer;

	public function __construct(DealerRepository $dealer)
	{
		$this->crud = new Crud();	
		$this->dealer = $dealer;	
	}

	public function index(Request $request)
	{ 
		$user          = Auth::user();
		$layoutColumns = $this->crud->layoutColumn();
		$form          = 'App\Storage\User\ProfileCrud@salesrepForm';

		if($user->salesRep && !$user->salesRep->is_agreement)
		{
			$request->session()->flash('show_salesrep_agree_modal', true);
		}

    	if($user->hasRole('csr'))
    	{
    		$form = 'App\Storage\User\ProfileCrud@csrForm';
    	}

    	$layoutColumns->addItemForm($form, ['view_args' => ['user' => $user]]);

		return $this->crud->pageView($layoutColumns);
	}

	public function csrSave(SettingsProfileSaveCsrRequest $request)
	{
		$user = Auth::user();
		$user->firstname                  = $request->get('firstname');
		$user->lastname                   = $request->get('lastname');
		$user->email                      = $request->get('email');		

		$user->setMeta('avatar_media_id', $request->get('avatar'));
		$user->save();
		$request->session()->flash('message', 'Profile has been updated.');
		
		return redirect($request->get('redirect_to'));	
	}

	/**
	 * Save BA profile settings
	 *
	 * @param      \App\Http\Requests\Admin\SettingsProfileSaveSalesRepRequest  $request  The request
	 *
	 * @return     Response
	 */
	public function save(SettingsProfileSaveSalesRepRequest $request)
	{
		$user                             = Auth::user();
		$showFields                       = $request->get('show_fields');
		$showEmail                        = ($showFields && in_array('show_email', $showFields));
		$showCell                         = ($showFields && in_array('show_cellphone', $showFields));
		$showOffice                       = ($showFields && in_array('show_officephone', $showFields));
		$dealer 						  = ($request->get('dealer') ? $this->dealer->skipPresenter()->find($request->get('dealer')) : null);
		$user->firstname                  = $request->get('firstname');
		$user->lastname                   = $request->get('lastname');
		$user->email                      = $request->get('email');

		$user->setMeta('avatar_media_id', $request->get('avatar'));
		$user->salesRep->dealers()->detach();

		if($dealer){
			$user->salesRep->dealers()->save($dealer);
		}
		
		$user->salesRep->cellphone        = $request->get('cellphone');
		$user->salesRep->officephone      = $request->get('officephone');
		$user->salesRep->facebook         = $request->get('facebook');
		$user->salesRep->twitter          = $request->get('twitter');
		$user->salesRep->linkedin         = $request->get('linkedin');
		$user->salesRep->youtube          = $request->get('youtube');
		$user->salesRep->instagram        = $request->get('instagram');
		$user->salesRep->pinterest        = $request->get('pinterest');
		$user->salesRep->show_cellphone   = $showCell;
		$user->salesRep->show_officephone = $showOffice;
		$user->salesRep->show_email       = $showEmail;
		$user->salesRep->job_title        = $request->get('jobtitle');
		$user->salesRep->email_work       = $request->get('work_email');
		//$user->salesRep->email_personal   = $request->get('personal_email');

		$user->save();
		$user->salesRep->save();

		if(!$user->salesRep->is_agreement)
		{

			//$request->session()->flash('show_salesrep_agree_modal', true);
		}else
		{

			$request->session()->flash('message', 'Profile has been updated.');
		}

		return redirect($request->get('redirect_to'));	
	}
}