<?php
namespace App\Http\Requests\Api;

use App\Http\Requests\Request;

/**
 * This request is for App\Http\Controllers\Api\Brand\BrandsController@store
 */
class BrandStoreRequest extends Request
{
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

            'name'        => 'required',
            // 'logo'        => 'required|image',
            'wp_category_id' => 'required|integer|exists:categories,wp_category_id',
            'catalog_url' => 'url|active_url',
            'opid'        => 'required',
            'wp_brand_id' => 'required|unique:brands,wp_brand_id'
            // 'slug'        =>  '',
            // 'image_url'   => 'url|active_url',
            // 'image_link'  => 'url|active_url',
            // 'image_text'  => '',

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The brand name field is required.',
            'opid.required' => 'The Ontraport tag field is required',
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
