<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Request;

/**
 * Use for simple API request with access token for a post.
 */
class CustomerNotificationBrandsRequest extends Request
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
     * @return     array
     */
    public function rules()
    {
        return [
            'access_token' => 'required',
            'customer_id'  => 'required|integer'
        ];
    }
}
