<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Request;

class RequestTypeContactStoreRequest extends Request
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
            'phone_number' => 'required',
            'body'         => 'required',
            'customer_id'  => 'required|integer|exists:user_customer,id',
            'offer_id'     => 'required|integer|exists:offers,id',
        ];
    }
}