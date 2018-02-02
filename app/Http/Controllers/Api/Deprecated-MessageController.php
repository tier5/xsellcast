<?php namespace App\Http\Controllers\Api;

/**
 * This is a deprecated file.
 * Need to investigate more where this is been use.
 */

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SimpleGetRequest;
use App\Storage\Messenger\MessageRepository;
use App\Http\Requests\Api\ThreadIndexRequest;
use App\Http\Requests\Api\ThreadReplyRequest;
use App\Storage\Messenger\ThreadRepository;
use App\Http\Requests\Api\ThreadShowRequest;
use App\Http\Requests\Api\MessageShowRequest;
use App\Http\Requests\Api\MessageDeleteRequest;
use App\Http\Requests\Api\MessageCreateRequest;

/**
 * @resource Messages
 *
 * Message resource.
 */
class MessageController extends Controller
{
	protected $message;
	protected $thread;

	public function __construct(MessageRepository $message, ThreadRepository $thread)
	{
		$this->message = $message;
		$this->thread  = $thread;
	}

	/**
	 * Thread
	 *
	 * Show all threads of a user.
	 *
	 * @param      \App\Http\Requests\Api\ThreadIndexRequest  $request  The request
	 *
	 * @return     <type>                                     ( description_of_the_return_value )
	 */
	public function thread(ThreadIndexRequest $request)
	{
		$user_id 	= $request->get('user_id');
		$type 		= $request->get('type');

		if($type == 'direct')
		{
			$type = 'message';
		}

		$threads = $this->thread->fromUser($user_id)->allNotNotes($type)->paginate(20);

		return response()->json($threads);
	}

	/**
	 * Show Thread
	 *
	 * Show thread info and list of messages.
	 *
	 * @param      \App\Http\Requests\Api\ThreadShowRequest  $request  The request
	 *
	 * @return     <type>                                    ( description_of_the_return_value )
	 */
	public function threadShow(ThreadShowRequest $request)
	{
		$user_id 	= $request->get('user_id');
		$threadId 	= $request->get('thread_id');
		$presenter 	= "App\Storage\Messenger\ThreadMessagesPresenter";
		$thread 	= $this->thread->setPresenter($presenter)->fromUser($user_id)->allNotNotes()->find($threadId);

		return response()->json($thread);
	}

	/**
	 * Show Message
	 *
	 * Show message info.
	 *
	 * @param      \App\Http\Requests\Api\MessageShowRequest  $request     The request
	 *
	 * @return     <type>                                   ( description_of_the_return_value )
	 */
	public function show(MessageShowRequest $request)
	{
		$message_id = $request->get('message_id');
		$message 	= $this->message->find($message_id);

		return response()->json($message);
	}

	/**
	 * Delete Message
	 *
	 * Delete a message.
	 *
	 * @param      \App\Http\Requests\Api\MessageDeleteRequest  $request     The request
	 *
	 * @return     <type>                                   ( description_of_the_return_value )
	 */
	public function destroy(MessageDeleteRequest $request)
	{
		$message_id = $request->get('message_id');
		$delete 	= $this->message->delete($message_id);

		return response()->json($delete);
	}

	/**
	 * Reply to Message
	 *
	 * Send reply to a thread.
	 *
	 * @param      \App\Http\Requests\Api\ThreadReplyRequest  $request  The request
	 *
	 * @return     <type>                                     ( description_of_the_return_value )
	 */
	public function reply(ThreadReplyRequest $request)
	{
		$sender 	= $request->get('sender_id');
		$threadId 	= $request->get('thread_id');
		$body 		= $request->get('body');
		$send 		= $this->message->create([
			'thread_id' => $threadId,
			'user_id'   => $sender,
			'body'      => $body]);

		return response()->json($send);
	}

	/**
	 * Create Message
	 *
	 * Create message for a type.
	 *
	 * @param      \App\Http\Requests\Api\MessageCreateRequest  $request  The request
	 */
	public function create(MessageCreateRequest $request)
	{

		$sender    = $request->get('sender_id');
		$recepient = $request->get('recepient_id');
		$body      = $request->get('body');
		$subject   = str_limit($body, 30, '');
		$type      = $request->get('type');
		$offerId   = $request->get('offer_id');
		$ret       = $this->thread->createMessage($sender, $recepient, $body, $type, $subject, $offerId);

		return response()->json($ret);
	}
}
