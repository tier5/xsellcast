<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Customer\CustomerRepository;
use App\Http\Requests\Api\CustomersRequest;
use App\Http\Requests\Api\CustomersShowRequest;
use App\Http\Requests\Api\SimpleGetRequest;
use App\Http\Requests\Api\CustomerSalesRepGetRequest;
use App\Http\Requests\Api\CustomerOfferPostRequest;
use App\Http\Requests\Api\CustomerOfferDeleteRequest;
use App\Storage\SalesRep\SalesRepRepository;
use App\Storage\Offer\OfferRepository;
use App\Http\Requests\Api\CustomerPostRequest;
use App\Http\Requests\Api\CustomerPutRequest;
use App\Http\Requests\Api\CustomerDeleteRequest;
use App\Storage\Customer\Customer;
use App\Storage\User\User;

/**
 * @resource Customer
 *
 * Customer resource.
 */
class CustomerController extends Controller
{
	protected $customer;

    protected $salesrep;

	public function __construct(CustomerRepository $customer, SalesRepRepository $salesrep, OfferRepository $offer)
	{
        $this->customer = $customer;
        $this->salesrep = $salesrep;
        $this->offer    = $offer;
	}

	/**
	 * All
	 *
	 * Get a list of customers.
	 *
	 * @param      \App\Http\Requests\Api\CustomersRequest  $request  The request
	 *
	 * @return     Response
	 */
    public function index(CustomersRequest $request)
    {
    	//$customers = $this->customer->paginate(20);

        $order = $request->get('sort', 'asc');
        $limit = $request->get('limit', 20);
        $rows  = $this->customer->scopeToUser();
        $rows  = ($rows ? $rows->orderBy('users.lastname', $order) : null);

        /**
         * Search by lastname or firstname
         */
        if($request->has('s') && $request->get('s') != '') {
            $rows = $rows->whereName($request->get('s'));
        }

        /**
         * Get list of prospects
         */
        $rows = ($rows ? $rows->paginate($limit) : null);

		return response()->json($rows);
    }

    /**
     * Single
     *
     * Get a customer by ID.
		 *
     * Return 404 if offer doesn't exist.
     *
     * @param      \App\Http\Requests\Api\CustomersShowRequest  $request    The request
     * @param      Integer  $customer_id  The customer identifier
     *
     * @return     Response
     */
    public function show(CustomersShowRequest $request, $customer_id)
    {
    	$customer = $this->customer->find($customer_id);

		return response()
			->json($customer);
    }

    /**
     * Brand Associates
     *
     * Get a list of brand associates related to a customer.
     *
     * @param      \App\Http\Requests\Api\CustomerSalesRepGetRequest  $request      The request
     * @param      Integer                                   $customer_id  The customer identifier
     *
     * @return     Response
     */
    public function salesReps(CustomerSalesRepGetRequest $request, $customer_id)
    {
        $filter       = $request->get('filter_by');
        $showApproved = true;
        $showRejected = true;

        if($filter == 'approved')
        {
            $showApproved = true;
            $showRejected = false;
        }elseif($filter == 'rejected')
        {
            $showApproved = false;
            $showRejected = true;
        }

        $customers = $this->salesrep->getByCustomer($customer_id, $showApproved, $showRejected)->paginate(20);

        return response()
            ->json($customers);
    }

    /**
     * Offers (lookbook)
     *
     * Get a list of offers related to a customer.
     *
     * @param      \App\Http\Requests\Api\SimpleGetRequest  $request      The request
     * @param      Integer                                   $customer_id  The customer identifier
     *
     * @return     Response
     */
    public function offers(SimpleGetRequest $request, $customer_id)
    {
        $offers = $this->offer->getByCustomer($customer_id)->paginate();

        return response()
            ->json($offers);
    }

    /**
     * Add Offer
     *
     * Add an offer related to a customer.
     *
     * @param      \App\Http\Requests\Api\CustomerOfferPostRequest  $request  The request
     *
     * @return     Response
     */
    public function addOffer(CustomerOfferPostRequest $request)
    {
        $customerId = $request->get('customer_id');
        $offerId    = $request->get('offer_id');
        $customer   = $this->customer->skipPresenter()->find($customerId);

        $this->customer->setOfferToCustomer($offerId, $customer);

       // $offer->customers()->save($customer);

        return response()
            ->json(array());
    }

    /**
     * Delete Offer
     *
     * Delete an offer related to customer.
     * The @parameter $_method is required and value must set to <strong>DELETE</strong>.
     *
     * @param      \App\Http\Requests\Api\CustomerOfferDeleteRequest  $request  The request
     *
     * @return     Integer Number of deleted rows.
     */
    public function deleteOffer(CustomerOfferDeleteRequest $request)
    {
        $customerId = $request->get('customer_id');
        $offerId    = $request->get('offer_id');
        $offer      = $this->customer->skipPresenter()
            ->find($customerId)
            ->pivotOffers()->where('offer_id', $offerId)->first(); //->delete();
        $offer->added = false;
        $offer->save();

        return response()
            ->json($offer);
    }

    /**
     * Create
     *
     * Create a new customer.
     *
     * @param      \App\Http\Requests\Api\CustomerPostRequest  $request  The request
     *
     * @return     <type>                                      ( description_of_the_return_value )
     */
    public function store(CustomerPostRequest $request)
    {
        try {
            $data             = $request->all();
            $data['password'] = uniqid();
            $data['geo_long'] = (isset($data['geo_long']) ? $data['geo_long'] : '');
            $data['geo_lat']  = (isset($data['geo_lat']) ? $data['geo_lat'] : '');
            $data['country']  = 'US';
            $data['provider']  = (isset($data['provider']) ? $data['provider'] : '');
            $data['provider_token']  = (isset($data['provider_token']) ? $data['provider_token'] : '');

            $customer         = $this->customer->createOne($data);

            // return response() ->json($this->customer->find($customer->id));
            return response()->json([
                    'status'=>true,
                    'code'=>config('responses.success.status_code'),
                    'data'=>$this->customer->find($customer->id),
                    'message'=>config('responses.success.status_message'),
                ], config('responses.success.status_code'));
            }
        catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'=>false,
                'code'=>config('responses.bad_request.status_code'),
                'data'=>null,
                'message'=>$e->getMessage()
            ],
                config('responses.bad_request.status_code')
            );
        }
    }

    /**
     * Update
     *
     * Update an existing customer.
     *
     * @param      \App\Http\Requests\Api\CustomerPutRequest  $request  The request
     *
     * @return     Response
     */
    public function update(CustomerPutRequest $request)
    {
        $custData = $request->except(['id', 'email', 'lastname', 'firstname']);
        $data     = $request->all();
        $customer = $this->customer->skipPresenter()->find($request->get('customer_id'));

        $this->customer->updateOne($customer, $data);

        return response()
            ->json($this->customer->skipPresenter(false)->find($customer->id));
    }

    /**
     * Delete
     *
     *  Delete an existing customer.
     *
     * @param      \App\Http\Requests\Api\CustomerDeleteRequest  $request  The request
     *
     * @return     Response
     */
    public function destroy(CustomerDeleteRequest $request)
    {
        $customer = Customer::find($request->get('customer_id'));

        if(!$customer)
        {
            return response()->json(['data' => 0]);
        }

        $userId = $customer->user->id;
        $customer->offers()->detach();

        foreach($customer->pivotOffers()->get() as $pivot)
        {
            $pivot->delete();
        }

        foreach ($customer->salesRepsPivot()->get() as $pivot) {
            $pivot->delete();
        }

        $customer->salesReps()->detach();

        foreach($customer->activities()->get() as $row)
        {
            $row->delete();
        }

        $customer->delete();
        User::find($userId)->delete();

        return response()->json(['data' => 1]);
    }
}
