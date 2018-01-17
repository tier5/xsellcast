<?php

namespace App\Http\Controllers\Debug;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\FullContact\FullContact;
use App\Storage\Crud\Crud;

class FullContactController extends Controller
{
	protected $fullcontact;

	public function __construct()
	{
		$this->fullcontact = new FullContact();
		$this->crud = new Crud();
	}

    public function index(Request $request)
    {
    	$email = $request->get('email');
    	$html = '';
    	
    	if($email){
	    	$person = $this->fullcontact->person()->lookupByEmail($email);
	    	$html = '<pre>' . print_r($person, true) . '</pre>';    		
    	}

    	$layoutColumns = $this->crud->layoutColumn();

    	$layoutColumns->addItemForm($this->form());
    	$layoutColumns->addItem($html, ['show_box' => false, 'column_size' => 8]);

		$this->crud->setLayoutTitle('FullContact');
//		$this->crud->getBreadCrumb()->add('FullContact API');

		return $this->crud->pageView($layoutColumns);
    }

    protected function form()
    {
      $fields = $this->crud->crudForm('get');
      $fields->setRoute('debug.fullcontact');
      $fields->addField(array(
        'name' => 'email',
        'label' => 'Email',
        'type' => 'text',
        'col-class' => 'col-md-12'));

      $fields->setSubmitText('Search');
      $fields->noRedirectField();

      $info = array(
        'box_title' => 'Full Contact - Person API', 
        'column_size' => 4,
        'column_class' => 'col-sm-12 col-xs-12');

      $box = $this->crud->box($info);
      $box->setForm($fields);

      return $box;
    }
}