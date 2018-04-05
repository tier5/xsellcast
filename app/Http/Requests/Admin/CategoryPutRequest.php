<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

/**
 * This request is for App\Http\Controllers\Admin\CategoriesController
 */
class CategoryPutRequest extends Request
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
            'name'   => 'required|unique:categories,name,'.$this->route('category_id'),
            'opid' => 'required',
            'slug' => 'slug|unique:categories,slug,'.$this->route('category_id'),
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The category name field is required.',
            'opid.required' => 'The Ontraport tag field is required',
            'slug.slug' => 'The Slug field is invalid',
        ];
    }

    public function forbiddenResponse()
    {
        return response()->view('errors.403');
    }
}
