<?php namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Crud\Crud;
use App\Storage\Messenger\MessageCrud;
use App\Storage\Offer\OfferRepository;
use App\Storage\Messenger\MessageRepository;
use App\Storage\Messenger\ThreadRepository;
use App\Http\Requests\Admin\MessageDirectSendRequest;
use App\Http\Requests\Admin\MessageDirectCreateRequest;
use App\Http\Requests\Admin\MessageDirectDraftSendRequest;
use \Auth;

class MessageNewController extends Controller
{
    
	protected $crud;

	protected $thread;

	protected $offer;

	protected $message;

	public function __construct(ThreadRepository $thread, OfferRepository $offer, MessageRepository $message)
	{
		$this->crud = new Crud();
		$this->thread = $thread;
		$this->offer = $offer;
		$this->message = $message;
	}

	public function index()
	{
		$layoutColumns = $this->crud->layoutColumn();
    	$layoutColumns->addItemForm('App\Storage\Messenger\MessageNewCrud@form');

		return $this->crud->pageView($layoutColumns);		
	}

	public function send(MessageDirectSendRequest $request)
	{
		$discard  = $request->get('discard'); // When discard there will no saving action will happen.
		$draft    = $request->get('draft');
		$send     = $request->get('send');
		$toUser   = $request->get('user');
		$authUser = Auth::user();
		$url      = route('admin.messages.sent');
		$msg      = null;
		$media   = $request->get('media');
		$offer   = $request->get('offer');
		$message = null;
		$body    = $request->get('body', '');

		if($send){
			/**
			 * Send and publish
			 *
			 */
			$thread  = $this->thread->createMessage($authUser->id, $toUser->id, $body, 'message', $request->get('subject'));
			$message = $thread->messages()->where('user_id', $authUser->id)->first();
			$msg     = 'Message has been sent.';
			$url     = route('admin.messages.show', ['thread_id' => $thread->id, 'message_id' => $message->id]);
		}elseif($draft){
			/**
			 * Save to draft
			 *
			 */
			$msg     = 'Message has been saved to drafts.';
			$thread  = $this->thread->createDraftMessage($authUser->id, $toUser->id, $body, 'message', $request->get('subject'));
			$message = $thread->messages()->where('user_id', $authUser->id)->first();
			$url     = route('admin.messages.draft.continue', [ 'thread_id' => $thread->id, 'message_id' => $message->id ]);
		
		}

		if(!$message)
		{
			//Discard message
			return redirect($url);
		}

		$message->media_ids = (is_array($media) ? implode(',', $media) : $media);
		$message->save();

		if($offer){
			$thread->setOffer($offer);
			$thread->save();
		}	
		
		$thread->markAsRead($authUser->id);
		$message->markAsRead($authUser->id);

		if($msg){
			$request->session()->flash('message', $msg);
		}

		return redirect($url);
	}

	public function continueDraft(MessageDirectCreateRequest $request, $thread_id = null, $message_id = null)
	{
		$layoutColumns = $this->crud->layoutColumn();
    	$layoutColumns->addItemForm('App\Storage\Messenger\MessageNewCrud@formDraft', ['view_args' => ['message' => $request->get('message'), 'user' => $request->get('user')]]);

		return $this->crud->pageView($layoutColumns);			
	}

	public function continueDraftSend(MessageDirectDraftSendRequest $request, $message_id = null)
	{ 
		$discard = $request->get('discard'); // When discard delete draft message/thread.
		$draft   = $request->get('draft');
		$send    = $request->get('send');
		$message = $request->get('message');
		$media   = $request->get('media');
		$offer   = $request->get('offer');
		$thread  = $message->threadWithDrafts;
		$user    = $request->get('user');
		$userTo  = $request->get('user_to');
		$url     = route('admin.messages.draft');
		$msg     = null;

		if($discard){
			$thread->delete();
			$message->delete();
			$msg = "Draft message has been removed!";
		}elseif($draft || $send){

			if($send){
				$thread->status = config('lbt.message_stat')['publish']['key'];
				$msg = 'Message has been sent.';
			}else{
				$msg = 'Message has been save to drafts.';
			}

			$message->body   = $request->get('body', '');
			$thread->subject = $request->get('subject');

			$thread->participants()->where('user_id', '!=', $user->id)->delete();
			$thread->participants()->create([
					'user_id' => $userTo->id,
					'thread_id' => $thread->id]);
			$thread->save();

			if($media){
				$message->media_ids = (!is_string($media) ? implode(',', $media) : $media);	
			}else{
				$message->media_ids = '';
			}
			

			if($offer){
				$thread->setOffer($offer);
			}

			$message->save();
			$thread->markAsRead($user->id);
			$message->markAsRead($user->id);
		}

		if($msg){
			$request->session()->flash('message', $msg);
		}

		return redirect($url);
	}

}