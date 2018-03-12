<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Request;
use App\Storage\Customer\Customer;
use App\Storage\Messenger\MessageParticipants;

class RequestAptShowRequest extends Request
{
    protected $redirectRoute = 'api.errors';

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

        $user    = Customer::find($this->customer_id)->user;
        // dd( $user);
          $pivotFound = false;

        if($user)
        {
            $pivot = $user->pivotParticipant()->where('thread_id', $this->message_id)->first();
            // dd($pivot);
            if($pivot)
            {
                $pivotFound = true;
            }
        }

        return [
            'access_token'  => 'required',
            'customer_id'   => 'required|exists:user_customer,id|is_message_assign:' . $pivotFound,
            'message_id'    => 'required|exists:messenger_threads,id,type,appt',
        ];
    }

     public function messages()
    {

        return [
            'customer_id.is_message_assign' => 'Prospect is not assigned to Action.'
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
    }
}