@if(($thread->getMeta('is_assign_to_other') && $custBaPivot) || (isset($showAcceptButton) && $showAcceptButton))
	@if(!$isApproved && !$isRejected)
		<button onclick="acceptLead(this)" class="btn btn-white btn-sm accept-lead" data-customer-user-id="{!! $talking_to->id !!}" data-salesrep-user-id="{!! $user->id !!}"><i class="fa fa-check-square"></i> Accept New Lead</button>
		<button class="btn btn-white btn-sm scrollto reject-lead" onclick="rejectLead(this)" data-customer-user-id="{!! $talking_to->id !!}" data-salesrep-user-id="{!! $user->id !!}" data-thread-id="{!! $thread->id !!}"><i class="fa fa-window-close"></i> Reject New Lead</button>
	@endif

	@if($isRejected)
		<span class="text-danger">Rejected</span>
	@endif

	@if($isApproved)
		<span class="text-success">Accepted</span>
	@endif
@else
	@include('admin.messages.show.parts.buttons')
@endif