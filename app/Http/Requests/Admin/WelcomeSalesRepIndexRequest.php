<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use Auth;

/**
 * This request is for App\Http\Controllers\Admin\WelcomeSalesRepController
 */
class WelcomeSalesRepIndexRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user       = $this->user();
        $isSalesRep = $user->hasRole('sales-rep');

        $this->attributes->add(['user' => $user]);

        return ($isSalesRep && $user->salesRep->is_agreement);
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
