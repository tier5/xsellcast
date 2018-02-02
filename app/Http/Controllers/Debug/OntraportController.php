<?php

namespace App\Http\Controllers\Debug;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Crud\Crud;
use App\Storage\Crud\Box;
use App\Storage\Crud\CrudForm;
use App\Storage\SalesRep\SalesRepRepository;

class OntraportController extends Controller
{
	protected $fullcontact;

	protected $salesrep;

	public function __construct(SalesRepRepository $salesrep)
	{
		$this->crud = new Crud();
		$this->salesrep = $salesrep;
	}

    public function index(Request $request)
    {
    	$layoutColumns   = $this->crud->layoutColumn();	

    	$layoutColumns->addItemForm($this->salesrepUpdateForm());

    	return $this->crud->pageView($layoutColumns);
    }

    public function salesrepUpdate(Request $request)
    {
    	$salesrepId = $request->get('salesrep_id');
    	$sr = $this->salesrep->skipPresenter()->find($salesrepId);
    	$fields = [];

    	foreach($request->all() as $k => $v)
    	{
    		if(trim($v) != '')
    		{
    			$fields[$k] = $v;
    		}
    	}

    	$this->salesrep->updateOne($sr, $fields);

    	$request->session()->flash('message', 'BA data has been updated!');

    	return redirect()->route('debug.admin.ontraport');  
    }

    protected function salesrepUpdateForm()
    {
		$fields = new CrudForm('post');
		$fields->setRoute('debug.admin.ontraport.salesrep.update');
		$list = collect([]);

		foreach($this->salesrep->skipPresenter()->all() as $sr)
		{
			$list->put($sr->id, ['name' => $sr->user->firstname . ' ' . $sr->user->lastname . '(' . $sr->user->email . ')', 'id' => $sr->id]); 
		}

		$info = array(
			'box_title' 	=> 'Sales Rep Modify', 
			'column_size' 	=> 12,
			'column_class' 	=> 'col-md-4 col-sm-12 col-xs-12');

		$fields->addField(array(
			'name' 			=> 'salesrep_id',
			'label' 		=> 'Sales Rep',
			'type' 			=> 'select',
			'list'			=> [ '-1' => 'Select salesrep...'] + $list->lists('name', 'id')->toArray(),
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
			'name' 			=> 'email',
			'label' 		=> 'Email name',
			'type' 			=> 'email',
			'col-class' 	=> 'col-md-12'));

		$box = new Box($info);
		$box->setForm($fields);		

		return $box;	
	}    

}