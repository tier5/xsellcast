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
            'wpid'          => 'integer|unique:offers,wpid',
            'wp_brand_id'      => 'required|integer|exists:brands,wp_brand_id',
            'auther_type' =>'required|in:brand,dealer,custom',
            'wp_dealer_id' =>'required_if:auther_type,dealer'
        ];
    }

}