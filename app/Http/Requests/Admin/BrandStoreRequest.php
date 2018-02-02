<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

/**
 * This request is for App\Http\Controllers\Admin\Brand\BrandsController@store
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
            'logo'        => 'required',
            'category'    => 'required',
            'catalog_url' => 'url|active_url',
            'opid' => 'required'];
    }

    public function messages()
    {
        return [
            'name.required' => 'The brand name field is required.',
            'opid.required' => 'The Ontraport tag field is required',
        ];
    }

    public function forbiddenResponse()
    {
        return response()->view('errors.403');
    }  
}
