<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Storage\Crud\Crud;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Storage\Customer\CustomerRepository;
use App\Storage\Csr\CsrRepository;

class UnmatchedLeadController extends Controller
{
	protected $crud;

	protected $customer;

	protected $csr;

	public function __construct(CustomerRepository $customer, CsrRepository $csr)
	{
		$this->crud = new Crud();
		$this->customer = $customer;
		$this->csr = $csr;
	}

	public function index(Request $request)
	{	
		$orderBy       = $request->get('field');
		$order         = $request->get('sort', 'desc');
		$layoutColumns = $this->crud->layoutColumn();
		$model         = $this->customer->skipPresenter()->noAssignedSalesrep();
		$table         = 'App\Storage\Customer\CustomerCrud@tableUnmatched';

		if($orderBy == 'name'){
			//$model->orderByRejected();
			$model->orderByName($order);	
		}elseif($orderBy == 'status'){

			$model->orderBySalesRepsPivot($order);
		}else{
			//$model->orderByRejected();
			
			$model->orderBy('updated_at', $order);
		}
		
    	$layoutColumns->addItemTable($table, $model->paginate(20));
		$this->crud->setExtra('sidemenu_active', 'admin_prospects');

		return $this->crud->pageView($layoutColumns);  
	}
}