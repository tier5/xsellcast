<?php namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Auth;
use App\Storage\Customer\CustomerRepository;
use App\Storage\Customer\CustomerSalesRep;

class BaHomeTopComposer
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
    public function compose(View $view)
    {
        $user          = Auth::user();
        $prospectCount = $user->salesRep()->first()->customers()->count();
        $newLeadCount  = $user->salesRep()->first()->pendingCustomers()->count();

        $view->with('newLeadCount', $newLeadCount);
        $view->with('prospectCount', $prospectCount);
    }
}