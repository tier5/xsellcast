<?php namespace App\Http\Controllers\Admin\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Storage\Customer\Customer;
use App\Storage\Brand\Brand;
use App\Storage\CustomerOffer\CustomerOffer;
use App\Storage\Dealer\DealerRepository;

class CustomerBaAssignmentController extends Controller
{
    
    protected $dealer;

	public function __construct(DealerRepository $dealer)
	{
		$this->dealer = $dealer;
	}

	public function index(Request $request, $customer_id)
	{
		$customer     = Customer::find($customer_id);
		$custSalesrep = $customer->salesRepsPivot()->orderBy('created_at', 'desc')->get();
		$custOffers   = CustomerOffer::where('customer_id', $customer_id);
		$brands       = Brand::whereHas('offers', function($q) use($customer_id){
			$q->whereHas('customers', function($q) use($customer_id){
				$q->where('user_customer.id', $customer_id);
			});
		})->get();

		foreach($brands as $k => $brand)
		{
			$brands[$k]->salesrep_approve  = false;
			$brand->salesrep_reject   = false;
			$brand->salesrep_pending  = false;
			$brand->salesrep_selected = null;
			$brand->customer = $customer;
			$brand->customer->user = $brand->customer->user;
		}

		foreach($brands as $k => $brand)
		{
			$hasFoundSelected = false;
			$salesreps             = collect([]);
			$brand->nearest_dealer = $brand->dealers->first();
			$brand->name_with_loc  = $brand->name . ' - ' . $brand->nearest_dealer->city  . ', ' . $brand->nearest_dealer->state;
			$custBrandFound        = $custOffers->whereHas('offer', function($q) use($brand){
				$q->whereHas('brands', function($q) use($brand){
					$q->where('brands.id', $brand->id);
				});
			})->first();

			foreach($custSalesrep as $row)
			{
				$salesrepFound = $row->salesrep()->whereHas('dealers', function($q) use($brand){
					$q->whereHas('brands', function($q) use($brand){
						$q->where('brands.id', $brand->id);
					});
				})->get();

				if($salesrepFound->count() > 0)
				{
					if(!$row->approved && !$row->rejected)
					{
						$brand->salesrep_pending  = true;
					}elseif($row->approved)
					{
						$brand->salesrep_approve = true;
					}else{

						$brand->salesrep_reject   = true;
					}
//dd($brand->salesrep_reject);
					$brand->salesrep_selected = $row->salesrep;
					$brand->salesrep_selected->user = $row->salesrep->user;				
				}
			}

			$dealers = $this->dealer->nearestInBrandCustomer($brand, $customer);

			foreach($dealers as $dealer)
			{

				/**
				 * List brand's BA that is near to customer.
				 */

		        foreach($dealer->salesReps as $salesrep)
		        {
					$state         = $dealer->state;
					$salesrep_name = $salesrep->user->firstname. ', ' . $salesrep->user->lastname . ' - ' . $dealer->city . ', ' . $state;
					$selected      = false;
					$fullname = $salesrep->user->firstname . ' ' . $salesrep->user->lastname;

		           	if($brand->salesrep_selected && $brand->salesrep_selected->id == $salesrep->id && !$brand->salesrep_reject)
		           	{
		           		$hasFoundSelected = true;
		           		$selected = true;
		           	}

		           	$salesreps->push(['id'=> $salesrep->id, 'name' => $salesrep_name, 'selected' => $selected, 'location' => $dealer->city . ', ' . $state, 'fullname' => $fullname]);
		        }

		        $brand->salesreps = $salesreps;
			}

	        /**
	         * When there is no found nearest dealer found with BA but it has already have some one BA is set but on pending.
	         */
	        if((($brand->salesreps->count() == 0 || !$hasFoundSelected) && $brand->salesrep_selected) && !$brand->salesrep_reject)
	        {
				$d             = $brand->salesrep_selected->dealers->first();
				$state         = $d->state;
				$salesrep_name = $brand->salesrep_selected->user->firstname. ', ' . $brand->salesrep_selected->user->lastname . ' - ' . $d->city . ', ' . $state;
				$fullname = $brand->salesrep_selected->user->firstname . ' ' . $brand->salesrep_selected->user->lastname;

	        	$salesreps->push(['id'=> $brand->salesrep_selected->id, 'name' => $salesrep_name, 'selected' => true, 'location' => $d->city . ', ' . $state, 'fullname' => $fullname]);
	        }			
		}

		//dd($brands);
		return response()->json(['data' => $brands]);
	}
}