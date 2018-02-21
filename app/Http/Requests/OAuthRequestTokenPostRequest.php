<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Response;

class OAuthRequestTokenPostRequest extends Request
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
            'client_id' => 'required|max:255',
            'client_secret' => 'required|max:255',
        ];
    }

    /**
     * Response error message as json
     *
     * @param array $errors
     * @return mixed
     */
    public function response(array $errors){
        return response()->json([
                'status'=>false,
                'code'=>config('responses.bad_request.status_code'),
                'data'=>null,
                'errors'=>$errors,
                'message'=>config('responses.bad_request.status_message'),
            ],
            config('responses.bad_request.status_code')
        );
        // return Response::json($errors, config('responses.bad_request.status_code'));
    }
}
