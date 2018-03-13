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
            'contents'      => 'required',
            'thumbnail_url' => 'url',
            'media'         => '',
            'status'        => 'required|in:publish,draft,pending',
            'title'         => 'required',
            'wpid'          => 'unique:offers,wpid',
            'brand_id'      => 'required|integer|exists:brands,id'
        ];
    }

}