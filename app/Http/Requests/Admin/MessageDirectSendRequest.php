<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Storage\Messenger\Thread;
use Auth;
use App\Storage\Customer\Customer;
use App\Storage\User\User;

/**
 * This request is for App\Http\Controllers\Admin\ProspectsController
 */
class MessageDirectSendRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user      = Auth::user();
        $isDiscard = $this->get('discard');
        $isDraft   = $this->get('draft');
        $email     = $this->get('to');
        $toUser    = User::where('email', $email)->first();

        if($isDiscard)
        {
            return true;
        }

        $this->attributes->add(['user' => $toUser]);

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user      = Auth::user();

        return [
            'to'        => 'required_with:send|required_with:draft|in_contact_email_of',
            'subject'   => 'required_with:send',
            'body'      => ''];
    }

    public function messages()
    {
        return [
            'to.in_contact_email_of' => "Invalid recipient email.",
            'to.required_with'       => "The to field is required.",
            'subject.required_with'  => "The subject field is required.",
            'body.required_with'     => 'The message body is required.' ];
    }

    public function forbiddenResponse()
    {
        return response()->view('errors.403');
    }    
}
