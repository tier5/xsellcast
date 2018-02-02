<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

/**
 * This request is for App\Http\Controllers\Admin\CategoriesController
 */
class CategoryStoreRequest extends Request
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
            'name'   => 'required',
            'opid' => 'required'];
    }

    public function messages()
    {
        return [
            'name.required' => 'The category name field is required.',
            'opid.required' => 'The Ontraport tag field is required'
        ];
    }

    public function forbiddenResponse()
    {
        return response()->view('errors.403');
    }  
}
