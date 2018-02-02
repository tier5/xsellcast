<?php namespace App\Http\ViewCreators;

use Illuminate\View\View;
use Auth;
use App\Storage\Customer\CustomerRepository;
use App\Storage\Customer\CustomerSalesRep;
use Illuminate\Http\Request;
use App\Storage\Messenger\Thread;

class NewLeadButtonsCreator
{
    protected $customer;

    public function __construct(CustomerRepository $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function create(View $view)
    {
        $req         = request();
        $user        = $req->user();
        $isRejected  = false;
        $isApproved  = false;
        $thread      = Thread::find($req->route('thread_id'));
        $customer    = $thread->users()->where('user_id', '!=', $user->id)->first()->customer;
        $salesrep    = $user->salesRep()->first();
        $custBaPivot = $customer->salesRepsPivot()->where('salesrep_id', $salesrep->id)->first();
        $isApproved  = ($custBaPivot ? $custBaPivot->approved : null);
        $isRejected  = ($custBaPivot ? $custBaPivot->rejected : null);
        $isPending   = ($custBaPivot ? $custBaPivot->isPending : null);   
             
        $view->with('isRejected', $isRejected);
        $view->with('isApproved', $isApproved);
        $view->with('custBaPivot', $custBaPivot);
    }
}