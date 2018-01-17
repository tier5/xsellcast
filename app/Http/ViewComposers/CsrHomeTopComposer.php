<?php namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Auth;
use App\Storage\Customer\CustomerRepository;
use App\Storage\Customer\CustomerSalesRep;

class CsrHomeTopComposer
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

        $unmatchedLeadCount       = $this->customer->skipPresenter()->noAssignedSalesrep()->countResult();
        $leadsPendBaApprovalCount = CustomerSalesRep::withPending()->count();

        $view->with('unmatchedLeadCount', $unmatchedLeadCount);
        $view->with('leadsPendBaApprovalCount', $leadsPendBaApprovalCount);
    }
}