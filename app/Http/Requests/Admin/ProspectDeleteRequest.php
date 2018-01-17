<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Storage\User\User;

/**
 * This request is for App\Http\Controllers\Admin\ProspectsController@delete
 */
class ProspectDeleteRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();

        if(!$user->hasRole('csr'))
        {

            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
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