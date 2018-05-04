<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Request;
use App\Storage\Customer\Customer;
use App\Storage\LbtWp\WpConvetor;

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

        $wp_customer_id=$this->get('wp_customer_id');
        $wp=new WpConvetor();

        $customer_id=$wp->getId('customer',$wp_customer_id);
        // $customerId = $this->get('customer_id');

        $customer   = Customer::find($customer_id);
        $wpIdRules  = 'unique:user_customer,wp_userid';
        $emailRules = 'unique:users,email';

        if($customer)
        {
            $wpIdRules .= ',' . $customer->id;
            $emailRules .= ',' . $customer->user->id;
        }else{
            return [
            'access_token'      => 'required',
            'wp_customer_id'    => 'bail|required|integer|exists:user_customer,wp_userid'
            ];
        }


   //     $this->attributes->add(['customer' => $customer]);

        return [
            'access_token'      => 'required',
            'wp_customer_id'    => 'bail|required|integer|exists:user_customer,wp_userid',
            // 'wp_userid'      => $wpIdRules,
            // 'address1'          => isset($this->address1)?'required':'',
            'address2'          => '',
            'zip'               => isset($this->zip)?'required|digits:5|integer':'',
            // 'city'              => isset($this->city)?'required':'',
            // 'state'             => isset($this->state)?'required':'',
            'geo_long'          => '',
            'geo_lat'           => '',
            'email'             => isset($this->email)?'required|email|regex:/^[_a-zA-Z0-9-+]+(\.[_a-z0-9-+]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z0-9]{2,3})$/|'.$emailRules:'',
            'firstname'         => isset($this->firstname)?'required':'',
            'lastname'          => isset($this->lastname)?'required':'',
            'cellphone'         => 'numeric',
            'officephone'       => 'numeric',
            'homephone'         => 'numeric',
            'avatar_url'        => 'url|active_url',


        ];
    }

    /**
     * Response error message as json
     *
     * @param array $errors
     * @return mixed
     */
    public function response(array $errors){

        return response()->json([
                    'status'=>false,
                    'code'=>config('responses.bad_request.status_code'),
                    'data'=>null,
                    'errors'=>$errors,
                    'message'=>config('responses.bad_request.status_message'),
                ],
                config('responses.bad_request.status_code')
            );
    }
}
