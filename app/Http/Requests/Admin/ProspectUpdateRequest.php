<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Storage\User\User;

/**
 * This request is for App\Http\Controllers\Admin\ProspectsController@update
 */
class ProspectUpdateRequest extends Request
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
        $custId = $this->route('customer_id');
        $custUser = User::whereHas('customer', function($q) use($custId){
            $q->where('id', $custId);
        })->first();

        return [
            'firstname' => 'required',
            'lastname'  => 'required',
            'email'     => 'required|unique:users,email,' . $custUser->id];
    }

    public function messages()
    {
        return [
            'firstname.required' => 'The first name field is required.',
            'last.required'      => 'The last name field is required.'];
    }

    public function forbiddenResponse()
    {
        return response()->view('errors.403');
    }  
}