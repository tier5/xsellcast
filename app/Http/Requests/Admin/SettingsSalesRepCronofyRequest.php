<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use Auth;

/**
 * This request is for App\Http\Controllers\Admin\Settings\SalesRepCronofyController
 */
class SettingsSalesRepCronofyRequest extends Request
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
        $rul='';
        if($user->salesrep->cronofy!=null){
            $rul=','.$user->salesrep->cronofy->id;
        }
        return [
             'client_id' => 'required|unique:salesrep_cronofy,client_id'.$rul,
             'client_secret'=> 'required|unique:salesrep_cronofy,client_secret'.$rul,
             'token'=> 'required|unique:salesrep_cronofy,token'.$rul,
             'calendar_name'=> 'required',
             'calendar_id'=> 'required|unique:salesrep_cronofy,calendar_id'.$rul,

            ];
    }

    public function messages()
    {
        return [

        ];
    }

    public function forbiddenResponse()
    {
        return response()->view('errors.403');
    }
}
