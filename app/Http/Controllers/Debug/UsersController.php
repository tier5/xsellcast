<?php namespace App\Http\Controllers\Debug;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Crud\Crud;
use App\Storage\Crud\TableCollection;
use App\Storage\Crud\Box;
use App\Storage\User\UserRepository;
use App\Storage\Crud\CrudForm;
use App\Storage\Customer\CustomerRepository;
use App\Storage\Dealer\Dealer;
use App\Storage\SalesRep\SalesRepRepository;
use App\Storage\Csr\CsrRepository;
use App\Storage\Ontraport\SalesRepObj;

class UsersController extends Controller
{
	protected $crud;

	protected $user;

	protected $customer;

	protected $salesrep;

	protected $csr;

    public function __construct(UserRepository $user, CustomerRepository $customer, SalesRepRepository $salesrep, CsrRepository $csr)
    {
		$this->crud     = new Crud();
		$this->user     = $user;
		$this->customer = $customer;
		$this->salesrep = $salesrep;
		$this->csr = $csr;
    }

    public function index()
    {

    //	$sro = new SalesRepObj();
   // 	dd($sro->fields());
    	///////

		$users           = $this->user->skipPresenter()->paginate(1000);
		$layoutColumns   = $this->crud->layoutColumn();	

		$layoutColumns->addItemForm($this->customerForm());
		$layoutColumns->addItemForm($this->salesRepForm());
		$layoutColumns->addItemForm($this->csrForm());
		$layoutColumns->addItemTable($this->usersTable($users), $users);

        /**
         * Generate page layout
         */
		//$this->crud->setLayoutTitle('Customer Actions');
		//$this->crud->getBreadCrumb()->add('Debug');

		return $this->crud->pageView($layoutColumns);	    	
    }

    protected function customerForm()
    {

		$fields = new CrudForm('post');
		$fields->setRoute('debug.admin.users.customer.store');

		$fields->addField(array(
			'name' 			=> 'email',
			'label' 		=> 'Email Address',
			'type' 			=> 'email',
			'col-class' 	=> 'col-md-12'));	

		$fields->addField(array(
			'name' 			=> 'firstname',
			'label' 		=> 'First name',
			'type' 			=> 'text',
			'col-class' 	=> 'col-md-12'));	

		$fields->addField(array(
			'name' 			=> 'lastname',
			'label' 		=> 'Last name',
			'type' 			=> 'text',
			'col-class' 	=> 'col-md-12'));	

		$fields->addField(array(
			'name' 			=> 'zip',
			'label' 		=> 'Zip',
			'type' 			=> 'text',
			'col-class' 	=> 'col-md-12'));	

		$fields->addField(array(
			'name' 			=> 'city',
			'label' 		=> 'City',
			'type' 			=> 'text',
			'col-class' 	=> 'col-md-12'));	

		$fields->addField(array(
			'name' 			=> 'state',
			'label' 		=> 'State Acronym',
			'type' 			=> 'text',
			'col-class' 	=> 'col-md-12'));	

		$fields->addField(array(
			'name' 			=> 'geo_lat',
			'label' 		=> 'Latitude',
			'type' 			=> 'text',
			'col-class' 	=> 'col-md-12'));	

		$fields->addField(array(
			'name' 			=> 'geo_long',
			'label' 		=> 'Longtitude',
			'type' 			=> 'text',
			'col-class' 	=> 'col-md-12'));	

		$info = array(
			'box_title' 	=> 'Debug - Customer Form', 
			'column_size' 	=> 12,
			'column_class' 	=> 'col-md-4 col-sm-12 col-xs-12');

		$box = new Box($info);
		$box->setForm($fields);		

		return $box;			    	
    }

    protected function salesRepForm()
    {

		$fields = new CrudForm('post');
		$fields->setRoute('debug.admin.users.salesrep.store');

		$fields->addField(array(
			'name' 			=> 'email',
			'label' 		=> 'Email Address',
			'type' 			=> 'email',
			'col-class' 	=> 'col-md-12'));	

		$fields->addField(array(
			'name' 			=> 'firstname',
			'label' 		=> 'First name',
			'type' 			=> 'text',
			'col-class' 	=> 'col-md-12'));	

		$fields->addField(array(
			'name' 			=> 'lastname',
			'label' 		=> 'Last name',
			'type' 			=> 'text',
			'col-class' 	=> 'col-md-12'));	

		$fields->addField(array(
			'name' 			=> 'dealer',
			'label' 		=> 'Dealer',
			'type' 			=> 'select',
			'list'			=> ['-1' => 'Select Dealer...'] + Dealer::get()->lists('name', 'id')->toArray(),
			'col-class' 	=> 'col-md-12'));	

		$info = array(
			'box_title' 	=> 'Debug - BA Form', 
			'column_size' 	=> 12,
			'column_class' 	=> 'col-md-4 col-sm-12 col-xs-12');

		$box = new Box($info);
		$box->setForm($fields);		

		return $box;			    	
    }

    protected function csrForm()
    {

		$fields = new CrudForm('post');
		$fields->setRoute('debug.admin.users.csr.store');

		$fields->addField(array(
			'name' 			=> 'email',
			'label' 		=> 'Email Address',
			'type' 			=> 'email',
			'col-class' 	=> 'col-md-12'));	

		$fields->addField(array(
			'name' 			=> 'firstname',
			'label' 		=> 'First name',
			'type' 			=> 'text',
			'col-class' 	=> 'col-md-12'));	

		$fields->addField(array(
			'name' 			=> 'lastname',
			'label' 		=> 'Last name',
			'type' 			=> 'text',
			'col-class' 	=> 'col-md-12'));

		$info = array(
			'box_title' 	=> 'Debug - CSR Form', 
			'column_size' 	=> 12,
			'column_class' 	=> 'col-md-4 col-sm-12 col-xs-12');

		$box = new Box($info);
		$box->setForm($fields);		

		return $box;			    	
    }

    public function customerStore(Request $request)
    {
    	$data = $request->all() + ['password' => 'lbt01LBT', 'country' => 'US'];
    	$this->customer->createOne($data);

        $request->session()->flash('message', 'Customer added!');

        return redirect()->route('debug.admin.users');     	
    }

    public function salesrepStore(Request $request)
    {
    	$this->salesrep->createOne($request->all() + ['password' => 'lbt01LBT'], $request->get('dealer'));

        $request->session()->flash('message', 'BA added!');

        return redirect()->route('debug.admin.users');       	
    }

    public function csrStore(Request $request)
    {
    	$data = $request->all() + ['password' => 'lbt01LBT', 'country' => 'US'];
		$this->csr->createOne($data); 

        $request->session()->flash('message', 'CSR added!');

        return redirect()->route('debug.admin.users'); 		   	
    }

    protected function usersTable($model)
    {
		$all = ($model ? $model->all() : [] );
	    $info = array(  'box_title' => 'All Users', 
			'column_size' => 12, 
			'column_class' => 'col-sm-12 col-xs-12',
			'box_float' => 'left');

		$tbl = $this->crud->tableCollection($all)
			->make($all)
	      	->columns(array(
	        	'name' => 'Name',
	        	'role' => 'Role',
	        	'email' => 'Email'
	      	))
	      	->modify('name', function($u){

	      		return $u->firstname . ' ' . $u->lastname;
	      	})
	      	->modify('role', function($u){
	      		$arr = [];
	      		
	      		foreach($u->roles as $role)
	      		{

	      			$arr[] = $role->name;
	      		}

	      		return implode(', ', $arr);
	      	})
	      	->modify('email', function($u){

	      		return $u->email;
	      	})
	      	->toActionShow(false)
	      	->useDefaultActions(false);

	    $box = $this->crud->box($info);
	    $box->setTable($tbl);    
	    
	    return $box;  		    	
    }
}