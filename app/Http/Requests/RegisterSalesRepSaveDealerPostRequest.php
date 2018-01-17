<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class RegisterSalesRepSaveDealerPostRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'dealer_id' => 'required'
        ];
    }

    public function messages()
    {

        return [
            'dealer_id.required' => "Oops, please select your dealer and then click 'Next'."];
    }
}
