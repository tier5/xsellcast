<?php namespace App\Http\Requests\Api;

use App\Http\Requests\Request;

class MessageCreateRequest extends Request
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
        return [
            'access_token'  => 'required',
            'type'          => 'required|in:appt,info,message,price',
            'sender_id'     => 'required',
            'recepient_id'  => 'required',
            'offer_id'      => 'required_if_not_messege_direct:type',
            'body'          => 'required'
        ];
    }

    public function messages()
    {
        return [
//            'subject.required_if_message_direct'        => 'Direct message required subject.',
            'offer_id.required_if_not_messege_direct'   => 'Offer ID is required.',
            'type.is_valid_message_type'                => 'Invalid message type key.'];
    }       
}
