<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

/**
 * This request is for App\Http\Controllers\Admin\InviteBaController
 */
class InviteBaSendRequest extends Request
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
            'firstname'   => 'required',
            'lastname'    => 'required',
            'jobtitle'    => '',
            'dealer'      => 'required',
            'email'       => 'required|email|unique:users,email',
            'cellphone'   => 'regex:/^\([0-9]{3}\) [0-9]{3}-[0-9]{4}$/',
            'officephone' => 'regex:/^\([0-9]{3}\) [0-9]{3}-[0-9]{4}$/',
            'facebook'    => '',
            'twitter'     => '',
            'linkedin'    => ''];
    }

    public function messages()
    {
        return [
        //    'officephone.size' => 'The office phone must be 10 digits.',
        //    'cellphone.size' => 'The cell phone must be 10 digits.'
        ];
    }

    public function forbiddenResponse()
    {
        return response()->view('errors.403');
    }  
}
