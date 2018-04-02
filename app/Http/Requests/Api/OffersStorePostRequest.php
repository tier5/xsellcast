<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Request;
//use Request;

class OffersStorePostRequest extends Request
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
            'access_token'  => 'required',
            'wpid'          => 'integer|unique:offers,wpid',
            'title'         => 'required',
            'contents'      => 'required',
            'thumbnail'     => 'image',
            'media'         => 'image',
            'status'        => 'required|in:publish,draft,pending',
            'wp_brand_id'   => 'required|integer|exists:brands,wp_brand_id',
            'auther_type'   =>'required|in:brand,dealer,custom',
            'wp_dealer_id'  =>'required_if:auther_type,dealer|exists:dealers,wpid'
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