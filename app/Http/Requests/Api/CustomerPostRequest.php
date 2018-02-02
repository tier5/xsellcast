<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Request;

class CustomerPostRequest extends Request
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
        return [
            'access_token' => 'required',
            'wp_userid'    => 'required|unique:user_customer,wp_userid',
            'address1'     => 'required',
            'address2'     => '',
            'zip'          => 'required',
            'city'         => 'required',
            'state'        => 'required',
            'geo_long'     => '',
            'geo_lat'      => '',
            'email'        => 'required|unique:users,email',
            'firstname'    => 'required',
            'lastname'     => 'required',
            'homephone'    => '',
            'cellphone'    => '',
            'officephone'  => ''
        ];
    }
}
