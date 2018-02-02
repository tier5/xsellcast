<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use Auth;

/**
 * This request is for App\Http\Controllers\Admin\Settings\ProfileController
 */
class SettingsProfileSaveSalesRepRequest extends Request
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
        $user = Auth::user();

        return [
            'firstname'      => 'required',
            'lastname'       => 'required',
            'jobtitle'       => '',
            'dealer'         => '',
            'email'          => 'required|email|unique:users,email,' . $user->id,
            'personal_email' => 'email',
            'work_email'     => 'email',
            'cellphone'      => 'regex:/^\([0-9]{3}\) [0-9]{3}-[0-9]{4}$/',
            'officephone'    => 'regex:/^\([0-9]{3}\) [0-9]{3}-[0-9]{4}$/',
            'facebook'       => '',
            'twitter'        => '',
            'linkedin'       => ''];
    }

    public function messages()
    {
        return [
           // 'officephone.size' => 'The office phone must be 10 digits.',
           // 'cellphone.size' => 'The cell phone must be 10 digits.',
            'email.required' => 'The personal email field is required.',
            'email.unique' => 'The personal email field must be unique.',
            'email.email' => 'The personal email field must be a valid email address.'
        ];
    }

    public function forbiddenResponse()
    {
        return response()->view('errors.403');
    }  
}
