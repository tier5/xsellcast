<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Request;

/**
 * Use for simple API request with access token for a post.
 */
class CustomerNotificationBrandRequest extends Request
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

        $input = $this->all();
        // $brands=explode(',', $this->get('brand_ids'));
        // $input['brand_ids']=$brands;
        $this->replace($input);
           $rules= [
            'access_token' => 'required',
            'wp_customer_id'  => 'required|integer|exists:user_customer,wp_userid',
            // 'brand_ids' => 'required',

            'wp_brands' => 'required|exists:brands,wp_brand_id',
            // 'status'  => 'required',
        ];

    // $brand_rule=[];
    //  foreach ($brands as $key=>$brand) {
    // $brand_rule['brand_ids.'.$key]=  'required|exists:brands,id';
    // // $tempb['brand'][$key]  = ['' => $brand];
    //  }

        // $rules= array_merge($rules,$brand_rule);
        // dd($rules);
        return $rules;
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
