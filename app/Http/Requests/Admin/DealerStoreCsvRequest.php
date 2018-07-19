<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

/**
 * This request is for App\Http\Controllers\Admin\Dealer\DealerController@store
 */
class DealerStoreCsvRequest extends Request {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'csv' => 'required',

        ];
    }

    public function messages() {
        return [
            'csv.required' => 'The dealer CSV field is required.',

        ];
    }

    public function forbiddenResponse() {
        return response()->view('errors.403');
    }
}