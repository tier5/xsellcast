<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Request;
use App\Storage\Customer\Customer;

/**
 * Use for simple API request with access token for a post.
 */
class CustomerOfferDeleteRequest extends Request
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
     * @return     array
     */
    public function rules()
    {
        $customer=Customer::find($this->customer_id);
        $offer_id= $this->offer_id;
        $pivotFound = false;

        if($customer)
        {
            $pivot = $customer->pivotOffers()->where('offer_id', $offer_id)->where('added','1')->first();
            // dd($pivot);
            if($pivot)
            {
                $pivotFound = true;
            }
        }
        return [
            'access_token' => 'required',
            // '_method'      => 'required|in:DELETE',
             'customer_id'  => 'required|integer|exists:user_customer,id|is_pivot_assign:' . $pivotFound,
            'offer_id'     => 'required|integer|exists:offers,id'
        ];
    }

    public function messages()
    {

        return [
            'customer_id.is_pivot_assign' => 'Prospect has not added offer in lookbook.'
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
        // return Response::json($errors, config('responses.bad_request.status_code'));
    }
}
