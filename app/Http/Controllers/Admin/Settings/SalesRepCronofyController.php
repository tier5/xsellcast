<?php namespace App\Http\Controllers\Admin\Settings;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Crud\Crud;
use Auth;
use App\Http\Requests\Admin\SettingsSalesRepCronofyRequest;
use App\Storage\SalesrepCronofy\SalesrepCronofy;
// use App\Storage\Messenger\Thread;
use App\Storage\SalesRep\SalesRepRepository;
use App\Storage\Cronofy\CronofyHttp;
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
		try{
			$user = Auth::user();
			// $user          = $salesrep->user;
			$layoutColumns = $this->crud->layoutColumn();
			$form          = 'App\Storage\SalesRep\SalesRepCrud@cronofySetting';

	    	$layoutColumns->addItemForm($form, ['view_args' => ['user' => $user, 'route' => 'admin.salesrep.update']]);

	    	$this->crud->setExtra('sidemenu_active', 'settings.salesrep.cronofysettings');

			return $this->crud->pageView($layoutColumns);
		 }
        catch (\Exception $e) {
            $request->session()->flash('message', $e->getMessage());
            return redirect()->back();
        }

	}
	public function update(SettingsSalesRepCronofyRequest $request)
	{
		try{
				$user = Auth::user();
				$client_id=$request->client_id;
				$client_secret=$request->client_secret;
				$token=$request->token;
				$calendar_name=$request->calendar_name;
				$calendar_id=$request->calendar_id;
				try{
				$cronofy=new CronofyHttp();
				$cronofy->client_id=$client_id;
				$cronofy->client_secret=$client_secret;

				$cronofyobj=	$cronofy-> revoke_authorization($token);
				}catch (\Exception $e) {
		        	$request->session()->flash('message', 'The cronofy details is invalid!');
		            return redirect()->back();
		        }

				if($user->salesrep->cronofy!=null){
					//update
					$cronofy=SalesrepCronofy::find($user->salesrep->cronofy->id);
				}else{
					//create
					$cronofy=new SalesrepCronofy();
				}
				$cronofy->salesrep_id=$user->salesrep->id;
				$cronofy->client_id=$client_id;
				$cronofy->client_secret=$client_secret;
				$cronofy->token=$token;
				$cronofy->calendar_name=$calendar_name;
				$cronofy->calendar_id=$calendar_id;
				$cronofy->save();
				$request->session()->flash('message', 'The cronofy details successfully added!');
				return redirect()->back();
		 }
	        catch (\Exception $e) {
            $request->session()->flash('message', $e->getMessage());
            return redirect()->back();
        }
	}
}
