<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

/**
 * This request is for App\Http\Controllers\Admin\Settings\ChangePasswordController@save
 */
class ChangePasswordSaveRequest extends Request
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
        $user = $this->user();
        $currentPassRules = 'required';
        
        $this->attributes->add(['user' => $user]);

        if(!$user->isFbUserNotPasswordSet())
        {
            $currentPassRules .= '|password_exist_to_user:' . $user->email;
        }

        return [
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
            'new_password_confirmation' => 'required'];
    }

    public function messages()
    {
        return [
            'current_password.password_exist_to_user' => 'Incorrect current password.',
       //     'new_password.confirmed' => 'Password confirm not matched.'
        ];
    }

    public function forbiddenResponse()
    {
        return response()->view('errors.403');
    }  
}
