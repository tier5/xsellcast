<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Api\SimpleGetRequest;
use App\Http\Requests\Api\SimpleListGetRequest;
use App\Storage\Customer\CustomerRepository;
use App\Storage\SalesRep\SalesRepRepository;
use Illuminate\Http\Request;

/**
 * @resource Sales Rep(BA)
 *
 * Sales Rep resource.
 */
class SalesRepController extends Controller {
    protected $salesrep;

    protected $customer;

    public function __construct(SalesRepRepository $salesrep, CustomerRepository $customer) {
        $this->salesrep = $salesrep;
        $this->customer = $customer;
    }

    /**
     * All
     *
     * Get a list of sales reps.
     *
     * @param      \Illuminate\Http\Request  $request  The request
     *
     * @return     Response
     */
    public function index(SimpleListGetRequest $request) {

        $salesReps = $this->salesrep->paginate(20);

        return response()
            ->json($salesReps);
    }

    /**
     * Single
     *
     * Get sales rep basic information.
     *
     * @param      \App\Http\Requests\Api\SimpleGetRequest  $request      The request
     * @param      Integer                                   $salesrep_id  The salesrep identifier
     *
     * @return     Response
     */
    public function show(SimpleGetRequest $request, $salesrep_id) {
        $salesRep = $this->salesrep->find($salesrep_id);

        return response()
            ->json($salesRep);
    }

    /**
     * Customers
     *
     *    Get a list of customers related to BA.
     *
     * @param      \App\Http\Requests\Api\SimpleListGetRequest  $request      The request
     * @param      <type>                                   $salesrep_id  The salesrep identifier
     *
     * @return     <type>                                   ( description_of_the_return_value )
     */
    public function customers(SimpleListGetRequest $request, $salesrep_id) {
        $salesRep = $this->salesrep->find($salesrep_id);
        $order    = $request->get('sort', 'asc');
        $limit    = $request->get('limit', 20);
        $rows     = null;
        $rows     = ($salesRep ? $this->customer->getBySalesRep($salesrep_id, true) : null);
        $rows     = ($rows ? $rows->orderBy('lastname', $order) : null);

        /**
         * Search by lastname or firstname
         */
        if ($request->has('s') && $request->get('s') != '') {
            $rows = $rows->whereName($request->get('s'));
        }

        /**
         * Get a list of prospects
         */
        $rows = ($rows ? $rows->paginate($limit) : null);

        return response()->json($rows);
    }
}
