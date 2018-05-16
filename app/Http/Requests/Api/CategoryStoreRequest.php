<?php
namespace App\Http\Requests\Api;

use App\Http\Requests\Request;

/**
 * This request is for App\Http\Controllers\Api\CategoriesController@store
 */
class CategoryStoreRequest extends Request {
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

            'name'           => 'required|unique:categories,name',
            'slug'           => 'slug|unique:categories,slug',
            'wp_category_id' => 'required|integer|unique:categories,wp_category_id',

        ];
    }

    public function messages() {
        return [
            'name.required' => 'The category name field is required.',
            'opid.required' => 'The Ontraport tag field is required',
            'slug.slug'     => 'The Slug field is invalid',
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
