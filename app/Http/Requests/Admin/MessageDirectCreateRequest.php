<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Storage\Messenger\Thread;
use App\Storage\Messenger\Message;
use Auth;
use App\Storage\Customer\Customer;

/**
 * This request is for App\Http\Controllers\Admin\ProspectsController
 */
class MessageDirectCreateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    { 
        $thread_id = $this->route('thread_id');
        $message_id = $this->route('message_id');
        $user = Auth::user();

        if($thread_id && !$message_id){
            return false;
        }

        //Pull only draft thread with a message.
        $message = Message::inThreadDraftOnly($thread_id)->where('user_id', $user->id)->find($message_id);

        if(!$message){

            return false;
        }

        $this->attributes->add(['user' => $user, 'message' => $message]);

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
            'to'        => 'required_with:send',
            'subject'   => 'required_with:send',
            'body'      => 'required_with:send'];
    }

    public function messages()
    {
        return [
            'to.required_with' => 'The to field is required.',
            'subject.required_with' => 'The subject field is required.',
            'body.required_with' => 'The body field is required.'];
    }

    public function forbiddenResponse()
    {
        return response()->view('errors.403');
    }    
}
