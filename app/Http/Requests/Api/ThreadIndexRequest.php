<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Request;

class ThreadIndexRequest extends Request
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
            'user_id'       => 'required',
            'type'          => 'is_valid_message_type|in:appt,info,message,price'
        ];
    }

    public function messages()
    {
        return [
            'type.is_valid_message_type'    => 'Invalid message type key.'];
    }    
}
