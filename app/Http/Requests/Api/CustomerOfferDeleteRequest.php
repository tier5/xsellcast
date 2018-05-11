<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Request;
use App\Storage\Customer\Customer;
use App\Storage\LbtWp\WpConvetor;


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

        // $this->customer_id
        // $offer_id= $this->offer_id

            $wp_offer_id        =   $this->wp_offer_id;
            $wp_customer_id     =   $this->wp_customer_id;
            $wp=new WpConvetor();
            $customer_id        =   $wp->getId('customer',$wp_customer_id);
            $offer_id           =   $wp->getId('offer',$wp_offer_id);

        $customer=Customer::find($customer_id);


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
            '_method'      => 'required|in:DELETE,delete',
            'wp_customer_id'  => 'required|integer|exists:user_customer,wp_userid|is_pivot_assign:' . $pivotFound,
            'wp_offer_id'     => 'required|integer|exists:offers,wpid'
        ];
    }

    public function messages()
    {

        return [
            'wp_customer_id.is_pivot_assign' => 'Prospect has not added offer in lookbook.'
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
