<?php namespace App\Http\Controllers\Admin;

use Auth;
use App\Storage\Crud\Crud;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InviteBaSendRequest;
use App\Storage\SalesRep\SalesRepRepository;
use App\Storage\User\UserRepository;
use App\Storage\Dealer\DealerRepository;

class InviteBaController extends Controller
{
    protected $salesrep;

    protected $user;

    protected $dealer;

	public function __construct(SalesRepRepository $salesrep, UserRepository $user, DealerRepository $dealer)
    {
        $this->crud     = new Crud();
        $this->salesrep = $salesrep;
        $this->user     = $user;
        $this->dealer   = $dealer;
    }

    public function index()
    {
        $user          = Auth::user();
        $layoutColumns = $this->crud->layoutColumn();
        
        $layoutColumns->addItemForm('App\Storage\SalesRep\BAInviteCrud@form');

		return $this->crud->pageView($layoutColumns);
    }

    public function send(InviteBaSendRequest $request)
    {
        $showFields                 = $request->get('show_fields');
        $showEmail                  = ($showFields && in_array('show_email', $showFields));
        $showCell                   = ($showFields && in_array('show_cellphone', $showFields));
        $showOffice                 = ($showFields && in_array('show_officephone', $showFields));
        $dealer                     = ($request->get('dealer') ? $this->dealer->skipPresenter()->find($request->get('dealer')) : null);
        $fields                     = $request->only([ 'firstname', 'lastname', 'jobtitle', 'dealer', 'email', 'cellphone', 'officephone', 'facebook', 'twitter', 'linkedin']);
        $fields['show_cellphone']   = $showCell;
        $fields['show_email']       = $showEmail;
        $fields['show_officephone'] = $showOffice;
        $fields['password']         = str_random(8);

        $user = $this->user->skipPresenter()->createSalesRep($fields);
        $salesrep = $user->salesRep;

        if($dealer){
            $re = $user->salesRep->dealers()->save($dealer);
        }

        /**
         * Set status to invited_unconfirm.
         */
        $user->saveAsUnConfirmedInvited();

        /**
         * Send mail confirmation.
         */
        $this->user->mailSalesRepInvited($user->salesRep, $fields['password']);     

        /**
         * Set BA agreement to false.
         */
        $salesrep->setFalseAgreement();

        /**
         * Set password changed value.
         */
        $salesrep->setToPasswordChanged(false);

        $request->session()->flash('message', 'Invitation has been sent to "' . $user->email . '"');   
        return redirect()->route('admin.brand.associate.invite');  
    }
}