<?php namespace App\Http\Controllers\Admin\SalesRep;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Crud\Crud;
use App\Storage\SalesRep\SalesRepRepository;
use App\Http\Requests\Admin\SalesRepUpdateRequest;
use App\Storage\Dealer\DealerRepository;

class SalesRepController extends Controller
{
	protected $salesrep;

	protected $dealer;

	protected $crud;

	public function __construct(SalesRepRepository $salesrep, DealerRepository $dealer)
	{
		$this->salesrep = $salesrep;
		$this->dealer   = $dealer;
		$this->crud     = new Crud;
	}

	public function index(Request $request, $author_type = null)
	{
		$layoutColumns = $this->crud->layoutColumn();
		$table         = 'App\Storage\SalesRep\SalesRepCrud@table';
		$model         = $this->salesrep->skipPresenter();
		$sort 		   = $request->get('sort', 'asc');
		$sortBy		   = $request->get('field', 'name');
		
		switch($sortBy)
		{	
			case 'name':
				$model = $model->orderByName($sort);
				break;
			case 'email':
				$model = $model->orderByEmail($sort);
				break;
			case 'agreement':
				$model = $model->orderByAgreement($sort);
				break;
			default:
				$model = $model->orderBy('created_at', $sort);
				break;
		}

    	$layoutColumns->addItemTable($table, $model->paginate(20));

		return $this->crud->pageView($layoutColumns);    	
	}

	public function show(Request $request, $salesrep_id)
	{
		$salesrep      = $this->salesrep->skipPresenter()->find($salesrep_id);

		if(!$salesrep)
		{
			abort('402', 'Invalid BA ID.');
		}

		$user          = $salesrep->user;
		$layoutColumns = $this->crud->layoutColumn();
		$form          = 'App\Storage\SalesRep\SalesRepCrud@editForm';

    	$layoutColumns->addItemForm($form, ['view_args' => ['user' => $user, 'route' => 'admin.salesrep.update']]);

    	$this->crud->setExtra('sidemenu_active', 'admin_ba');
    	
		return $this->crud->pageView($layoutColumns);       	
	}

	public function update(SalesRepUpdateRequest $request, $salesrep_id)
	{
		//$salesrep =  $request->get('salesrep');
		$user = $request->get('user');
		
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

		$user->save();
		$user->salesRep->save();

		$request->session()->flash('message', 'BA has been updated.');

		return redirect()->route('admin.salesrep.show', ['salesrep_id' => $salesrep_id]);			
	}

/*
	public function show(Request $request, $salesrep_id)
	{
		$layoutColumns = $this->crud->layoutColumn();
		$salesrep      = $this->salesrep->skipPresenter()->find($salesrep_id);
		$opts          = ['show_box' => false, 'column_class' => 'm-b-md', 'column_size' => 12, 'view_args' => compact('salesrep')];
		
    	$layoutColumns->addItem('admin.salesrep.show', $opts);
    	$this->crud->setExtra('sidemenu_active', 'admin_ba');
    	
		return $this->crud->pageView($layoutColumns);    
	}
	*/
}

?>