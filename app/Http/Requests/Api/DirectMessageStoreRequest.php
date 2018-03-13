<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Request;
use App\Storage\Customer\Customer;
use App\Storage\SalesRep\SalesRep;

class DirectMessageStoreRequest extends Request
{
    protected $redirectRoute = 'api.errors';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $customer = Customer::find($this->get('customer_id')); //$this->customer->skipPresenter()->find($request->get('customer_id'));
        $salesrep = SalesRep::find($this->get('salesrep_id')); //$this->salesrep->skipPresenter()->find($request->get('salesrep_id'));
        $pivotFound = false;

        if($customer && $salesrep)
        {
            $pivot = $salesrep->customersPivot()->where('customer_id', $customer->id)->first();
            if($pivot)
            {
                $pivotFound = true;
            }
        }

        return [
            'access_token'  => 'required',
            'customer_id'   => 'required|integer|exists:user_customer,id|is_salesrep_assign:' . $pivotFound,
            'salesrep_id'   => 'required|integer|exists:user_salesreps,id',
         //   'message_id'    => 'required',
            'body'          => 'required',
            'offer_id'      => 'integer|exists:offers,id'
        ];
    }

    public function messages()
    {

        return [
            'customer_id.is_salesrep_assign' => 'Prospect is not assigned to BA or BA not approve.'
        ];
    }
}