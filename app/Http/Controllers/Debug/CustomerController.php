<?php

namespace App\Http\Controllers\Debug;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Dealer\DealerRepository;
use App\Storage\Crud\Crud;
use App\Storage\Crud\CrudForm;
use App\Storage\Crud\Box;
use App\Storage\Cronofy\CronofyHttp;
use App\Storage\Customer\CustomerRepository;
use App\Storage\Offer\OfferRepository;
use App\Storage\Messenger\ThreadRepository;
use \Auth;
use DB;
use App\Storage\CustomerRequest\CustomerRequest;
use App\Storage\SalesRep\SalesRep;
use App\Storage\Offer\Offer;

class CustomerController extends Controller
{
	protected $customer;

	protected $offer;

	protected $thread;

	protected $custemer_request;

	public function __construct(CustomerRepository $customer, OfferRepository $offer, ThreadRepository $thread)
	{
		$this->customer = $customer;
		$this->offer = $offer;
		$this->crud = new Crud();
		$this->thread  = $thread;
		$this->customer_request = new CustomerRequest();
	}

	public function actions()
	{
		$customer = $this->customer->skipPresenter()->paginate(1000);
		$layoutColumns = $this->crud->layoutColumn();	

		$layoutColumns->addItemTable($this->editTableBox($customer), $customer);

        /**
         * Generate page layout
         */
		//$this->crud->setLayoutTitle('Customer Actions');
		//$this->crud->getBreadCrumb()->add('Debug');

		return $this->crud->pageView($layoutColumns);	
	}

	protected function editTableBox($model)
	{
		$all = ($model ? $model->all() : [] );
	    $info = array(  'box_title' => 'All Prospects', 
			'column_size' => 12, 
			'column_class' => 'col-sm-12 col-xs-12',
			'box_float' => 'left');

		$tbl = $this->crud->tableCollection($all)
			->make($all)
	      	->columns(array(
	        	'name' => 'Name'
	      	))
	      	->modify('name', function($c){

	      		if(!isset($c->user->firstname)){
	      			return '';
	      		}
	      		return $c->user->firstname . ' ' . $c->user->lastname;
	      	})
	      	->useDefaultActions(false)
	      	->addAction(function($c){

				return [
                    'label' => 'Request Appt',
                    'url'	=> route('debug.customer.action.request', ['type' => 'appt', 'customer_id' => $c->id]),
                    'key'	=> 'request.appt'];
	      	})
	      	->addAction(function($c){

				return [
                    'label' => 'Request Price',
                    'url'	=> route('debug.customer.action.request', ['type' => 'price', 'customer_id' => $c->id]),
                    'key'	=> 'request.price'];
	      	})
	      	->addAction(function($c){

				return [
                    'label' => 'Request Info',
                    'url'	=> route('debug.customer.action.request', ['type' => 'info', 'customer_id' => $c->id]),
                    'key'	=> 'request.info'];
	      	})
	      	->addAction(function($c){

				return [
                    'label' => 'Request Contact Me',
                    'url'	=> route('debug.customer.action.request', ['type' => 'contact_me', 'customer_id' => $c->id]),
                    'key'	=> 'request.contact_me'];
	      	})	      	
	      	->addAction(function($c){

				return [
                    'label' => 'Direct Messsage',
                    'url'	=> route('debug.customer.action.request', ['type' => 'message', 'customer_id' => $c->id]),
                    'key'	=> 'request.direct'];
	      	})
	      	->addAction(function($c){

				return [
                    'label' => 'Add Offer',
                    'url'	=> route('debug.customer.action.request', ['type' => 'offer-add', 'customer_id' => $c->id]),
                    'key'	=> 'request.offer.add'];
	      	});
	      	/*
	      	->addAction(function($c){

				return [
                    'label' => 'Request Info',
                    'url'	=> route('debug.customer.action.request.info', $c->id),
                    'key'	=> 'request.info'];
	      	})
	      	->addAction(function($c){

				return [
                    'label' => 'Add Offer',
                    'url'	=> route('debug.customer.action.add.offer', $c->id),
                    'key'	=> 'add.offer'];
	      	}); */

	    $box = $this->crud->box($info);
	    $box->setTable($tbl);    
	    
	    return $box;  		
	}

	public function request(Request $request, $type = null, $customer_id = null)
	{
		$box = $this->requestForm($request);
		$layoutColumns = $this->crud->layoutColumn();
    	$layoutColumns->addItemForm($box);

		//$this->crud->getBreadCrumb()->add('Debug')->active();

		return $this->crud->pageView($layoutColumns);
	}

	protected function requestForm($request)
	{
		$salesrep = Auth::user()->SalesRep;
		$customer = $this->customer->skipPresenter()->find($request->route('customer_id'));
		$offers = collect([]);
		
		foreach (Offer::get() as $offer) 
		{
			$offers->push($offer);
		}

		$fields = new CrudForm('post');
		$fields->setRoute('debug.customer.action.request.send');
		$list = [];

		foreach($offers as $offer)
		{
			$b = $offer->brands->first();
			$list[$offer->id] = ($b ? $offer->title . ' - ' . $b->name : 'n/a' );
		}

	//	if($request->route('type') != 'message'){
			$fields->addField(array(
				'name' 			=> 'offer',
				'label' 		=> 'Offer',
				'type' 			=> 'select',
				'list'			=>  ['' => 'Select offer...'] + $list,
				'col-class' 	=> 'col-md-6'));
	//	}else{
	//		$fields->addField(array(
	//			'name' 			=> 'subject',
	//			'label' 		=> 'Subject',
	//			'type' 			=> 'text',
	//			'col-class' 	=> 'col-md-6'));			
	//	}

		$fields->addField(array(
			'name' 			=> 'type',
			'label' 		=> 'Request Type',
			'type' 			=> 'hidden',
			'value'			=> $request->route('type'),
			'col-class' 	=> 'col-md-12'));

		$fields->addField(array(
			'name' 			=> 'cust_name',
			'label' 		=> 'Customer',
			'type' 			=> 'text',
			'value'			=> $customer->user->firstname . ' ' . $customer->user->lastname,
			'field-attr'	=> ['disabled' => 'disabled'],
			'col-class' 	=> 'col-md-6'));

		$fields->addField(array(
			'name' 			=> 'customer',
			'label' 		=> 'Customer',
			'type' 			=> 'hidden',
			'value'			=> $request->route('customer_id'),
			'col-class' 	=> 'col-md-12'));

		$fields->addField(array(
			'name'      => 'phone_number',
			'label'     => 'Phone number',
			'type'      => 'text',
			'clear_all' => true,
			'col-class' => 'col-md-6'));

		if($request->route('type') != 'offer-add'){
			$fields->addField(array(
				'name' 			=> 'body',
				'label' 		=> 'Message',
				'type' 			=> 'textarea',
				'col-class' 	=> 'col-md-6'));
		}

		$info = array(
			'box_title' 	=> 'Debug - Send Request', 
			'column_size' 	=> 12,
			'column_class' 	=> 'col-sm-12 col-xs-12');

		$box = new Box($info);
		$box->setForm($fields);

		return $box;		
	}	

	public function requestSend(Request $request)
	{
		$type        = $request->get('type');
		$offerId     = $request->get('offer'); 
		$offer       = ($offerId ? $this->offer->skipPresenter()->find($offerId) : null); 
		$customerId  = $request->get('customer');	
		$phoneNumber = $request->get('phone_number');
		$subject     = ''; //$request->get('subject');
		$customer    = $this->customer->skipPresenter()->find($customerId);
		$body        = $request->get('body');
		$user        = Auth::user();
		$thread      = null;
		$msg         = 'Action sent';

		if($type == 'message')
		{
			$subject = str_limit($body, 30, '');
		}

		if($type == 'message'){
			$thread = $this->thread->createMessage($customer->user->id, $user->id, $body, 'message', $subject, (!empty($offerId) ? $offerId : null));
		}elseif($type == 'contact_me' && $offer){
			$thread = $this->customer_request->sendContactRequest($customer, $offer, $body, $phoneNumber);
		}elseif($type != 'offer-add' && $offer){
			$thread = $this->customer_request->sendRequest($customer, $offer, $type, $body, $subject);
		}elseif($offer){
			//Add offer
			$this->customer->setOfferToCustomer($offerId, $customer);
		}
		
		

		if($thread)
		{
			$salesrepUser = $thread->users()->where('users.id', '!=', $customer->user->id)->first();
			$msg = 'Request sent to ' . $salesrepUser->email;
		}

        $request->session()->flash('message', $msg);

        return redirect()->route('debug.customer.actions');  		
	}
}