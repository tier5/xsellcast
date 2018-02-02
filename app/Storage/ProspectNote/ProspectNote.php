<?php

namespace App\Storage\ProspectNote;

use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use App\Storage\Messenger\Thread;
use Carbon\Carbon;

class ProspectNote
{

	public function noteMessages($user_id, $recipient_id)
	{ 
		$note = $this->note($user_id, $recipient_id);
	
		return ($note ? $note->messages()->orderBy('created_at', 'desc')->get() : null);
	}

	protected function note($user_id, $recipient_id)
	{
		return Thread::getAllLatest()->whereHas('participants', function($q) use($user_id){	
			$q->where('user_id', $user_id);
		})->where('type', 'note')->whereHas('participants', function($q) use($recipient_id){
			$q->where('user_id', $recipient_id);
		})->first();	
	}

	/**
	 * Submit message thrend to a prospect.
	 */
	public function postNote($sender_id, $recipient_id, $message)
	{
		$user_id = $sender_id;
		$thread = $this->note($user_id, $recipient_id);

		if(!$thread){
			$thread = Thread::create(
			    [
			        'subject' => 'Note',
			        'type'	  => 'note'
			    ]
			);			
		}

		// Message
		Message::create(
		    [
		        'thread_id' => $thread->id,
		        'user_id'   => $user_id,
		        'body'      => $message,
		    ]
		);
		
		// Sender
		Participant::create(
		    [
		        'thread_id' => $thread->id,
		        'user_id'   => $user_id,
		        'last_read' => new Carbon,
		    ]
		);
		
		$thread->addParticipant($recipient_id);

		return $thread;
	}

}