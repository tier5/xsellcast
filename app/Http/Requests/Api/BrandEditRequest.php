<?php
namespace App\Http\Requests\Api;

use App\Http\Requests\Request;

/**
 * This request is for App\Http\Controllers\Api\Brand\BrandsController@edit
 */
class BrandEditRequest extends Request {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {

        return [
            'wp_brand_id'    => 'required|exists:brands,wp_brand_id',
            'name'           => isset($this->name) ? 'required' : '',
            'logo'           => isset($this->logo) ? 'required|image' : '',
            'wp_category_id' => isset($this->category_id) ? 'required|integer|exists:categories,wp_category_id' : '',
            'catalog_url'    => isset($this->catalog_url) ? 'url|active_url' : '',
            'slug'           => 'slug|unique:brands,slug',
            'image_url'      => 'url|active_url',
            'image_link'     => 'url|active_url',
            'image_text'     => 'url|active_url',
            // 'opid'        => isset($this->opid)?'required':'',

        ];
    }

    public function messages() {
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
    public function response(array $errors) {

        return response()->json([
            'status'  => false,
            'code'    => config('responses.bad_request.status_code'),
            'data'    => null,
            'errors'  => $errors,
            'message' => config('responses.bad_request.status_message'),
        ],
            config('responses.bad_request.status_code')
        );
        // return Response::json($errors, config('responses.bad_request.status_code'));
    }
}
