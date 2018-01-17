<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use Auth;

/**
 * This request is for App\Http\Controllers\Admin\Settings\ProfileController
 */
class SettingsProfileSaveCsrRequest extends Request
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
            'firstname' => 'required',
            'lastname'  => 'required',
            'email'     => 'required|email|unique:users,email,' . $user->id];
    }

    public function messages()
    {
        return [];
    }

    public function forbiddenResponse()
    {
        return response()->view('errors.403');
    }  
}
