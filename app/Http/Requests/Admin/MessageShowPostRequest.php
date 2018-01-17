<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Storage\Messenger\Thread;
use Auth;

/**
 * This request is for App\Http\Controllers\Admin\ProspectsController
 */
class MessageShowPostRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $thread_id = $this->route('thread_id');
        $user = Auth::user();
        $thread = Thread::find($thread_id);

        if(!$thread){

            return false;
        }

        $this->attributes->add(['thread' => $thread, 'user' => $user]);

        return $thread->hasParticipant($user->id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'message' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'message.required'    => 'Oops, an offer message is required.'];
    }

    public function forbiddenResponse()
    {
        return response()->view('errors.403');
    }    
}
