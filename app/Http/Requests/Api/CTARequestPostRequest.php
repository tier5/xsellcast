<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Request;

class CTARequestPostRequest extends Request
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
            'access_token'      => 'required',
            'type'              => 'required|integer|in:1,2,3,4,5',
            'wp_customer_id'    => 'required|integer|exists:user_customer,wp_userid',
            'wp_offer_id'       => 'required|integer|exists:offers,wpid',
            'body'              => 'required_if:type,==,5',
            'phone_number'      => 'required_if:type,==,4|numeric',
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