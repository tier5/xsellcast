<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Storage\Dealer\Dealer;

/**
 * This request is for App\Http\Controllers\Admin\Dealer\DealerController@update
 */
class DealerUpdateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $dealer = Dealer::find($this->route('dealer_id'));
        $this->attributes->add(['dealer' => $dealer]);

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
            'name'               => 'required',
            'phone'              => 'required|regex:/^\([0-9]{3}\) [0-9]{3}-[0-9]{4}$/',
            'city'               => 'required',
            'state'              => 'required',
            'zip'                => 'required',
            'address1'           => 'required',
            'hours_of_operation' => 'valid_hoo'];
    }

    public function messages()
    {
        return [
            'name.required' => 'The dealer name field is required.',
            'hours_of_operation.valid_hoo' => 'Invalid time for hours of operation.'
        ];
    }

    public function forbiddenResponse()
    {
        return response()->view('errors.403');
    }  
}