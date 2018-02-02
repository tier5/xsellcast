<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Storage\Messenger\Thread;
use App\Storage\Messenger\Message;
use Auth;
use App\Storage\Customer\Customer;
use App\Storage\User\User;

/**
 * This request is for App\Http\Controllers\Admin\ProspectsController
 */
class MessageDirectDraftSendRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $message_id = $this->route('message_id');
        $user       = Auth::user();
        $email      = $this->get('to');
        $userTo     = User::where('email', $email)->first();
        $message    = Message::draftOnly()->where('user_id', $user->id)->find($message_id);
        $this->attributes->add([ 'user_to' => $userTo, 'user' => $user, 'message' => $message]);

        if($user->hasRole('csr'))
        {
            return true;
        }

    //    if($email && !$userTo->hasRole('customer'))
    //    {
    //        return false;
    //    }

        if(!$message_id)
        {
            return false;
        }

        
        if(!$message){

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

        return [ 'to' => 'required|in_contact_email_of'];
    }

    public function messages()
    {

        return [
             'to.in_contact_email_of' => "Bad email address." ];
    }

    public function forbiddenResponse()
    {
        return response()->view('errors.403');
    }    
}
