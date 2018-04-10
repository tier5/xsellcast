<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Request;

/**
 * Use for simple API request with access token for a post.
 */
class CustomerNearestDealerRequest extends Request
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
            'brand_id'  => 'required|integer|exists:brands,id',
            'ip'=>'ip|required_without_all:geo_lat,geo_long',
            // 'zip'=>'integer|required_without_all:geo_lat,geo_long,ip',
            'geo_lat'=>'lat|required_with_all:geo_long|required_without_all:ip',
            'geo_long'=>'lng|required_with_all:geo_lat|required_without_all:ip',
            'keyword'=>'',
            'per_page' => 'integer',
            'page' => 'integer',


        ];
    }

    public function messages(){
        return [
                'geo_lat.lat'=>'The latitude is invalid',
                'geo_long.lng'=>'The longitude is invalid',
                'geo_lat.required_without_all'=>'The geo latitude field is required when ip is not present.',
                'geo_long.required_without_all'=>'The geo longitude field is required when ip is not present.',


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
