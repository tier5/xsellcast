@if($thread->type != 'lead_reassign')
<button class="btn btn-white btn-sm scrollto" data-toggle="collapse" data-target="#messageReplyBox">
	<i class="fa fa-reply"></i> Reply</button>
<a href="#" class="btn btn-white btn-sm hidden" data-toggle="tooltip" data-placement="top" title="Set Appointment">
	<i class="fa fa-calendar"></i> Set Appt</a>
<a href="#" onclick="printPage('{!! route('admin.messages.show.print', ['message_id' => $message_id]) !!}'); return false;" class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Print email">
	<i class="fa fa-print"></i> </a>
@endif
<a href="#" class="btn btn-white btn-sm" title="Move to trash" data-toggle="modal" data-target="#deleteMessage" data->
	<i class="fa fa-trash-o"></i> </a>


<div class="modal inmodal" id="deleteMessage" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content animated bounceInRight">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	            <h4 class="modal-title">Delete forever?</h4>
	        </div>
	        <div class="modal-body">
	            <p>Are you sure you'd like to delete this message permanently?</p>
	        </div>
	        <div class="modal-footer">
	            <button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>
	            <a href="{!! route('admin.messages.delete', ['thread_id' => $thread->id]) !!}" class="btn btn-danger">Delete Message</a>
	        </div>
	    </div>
	</div>
</div>	