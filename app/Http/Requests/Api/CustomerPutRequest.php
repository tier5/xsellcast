<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Request;
use App\Storage\Customer\Customer;

class CustomerPutRequest extends Request
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
        $customerId = $this->get('customer_id');
        $customer   = Customer::find($customerId);
        $wpIdRules  = 'unique:user_customer,wp_userid';
        $emailRules = 'unique:users,email';

        if($customer)
        {
            $wpIdRules .= ',' . $customer->id;
            $emailRules .= ',' . $customer->user->id;
        } 

   //     $this->attributes->add(['customer' => $customer]);

        return [
            'access_token' => 'required',
            'customer_id'  => 'required|exists:user_customer,id',
            'wp_userid'    => $wpIdRules,
            'address1'     => '',
            'address2'     => '',
            'zip'          => '',
            'city'         => '',
            'state'        => '',
            'geo_long'     => '',
            'geo_lat'      => '',
            'email'        => $emailRules,
            'firstname'    => '',
            'lastname'     => '',
            'cellphone'    => '',
            'officephone'  => '',
            'homephone'    => ''
        ];
    }
}
