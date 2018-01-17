<div class="mail-box-header">
	<div class="row">
		<div class="col-md-6">
			<h2>@if(isset($boxTitle)){!! $boxTitle !!}@else{!! 'Inbox' !!}@endif ({!! $thread_count !!})</h2>
		</div>
		<div class="col-md-6">
		    <div class="mail-tools tooltip-demo">
		        <div class="btn-group pull-right">
		        	@if((isset($showAction) && $showAction) || !isset($showAction))
				        <a href="{!! url()->current() !!}" class="btn btn-white btn-md" data-toggle="tooltip" data-placement="left" title="Refresh inbox"><i class="fa fa-refresh"></i> Refresh</a>
				        <button class="btn btn-white btn-md hidden" data-toggle="tooltip" data-placement="left" title="Settings"><i class="fa fa-gear"></i> </button>
						<button type="button" class="btn btn-white btn-md" title="Move to trash" onclick="messageDeleteListModal(this)"><i class="fa fa-trash-o"></i> </button>
					@endif
					<div class="modal inmodal" id="deleteMessageListModal" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog modal-sm">
							<div class="modal-content animated bounceInRight">
						        <div class="modal-header">
						            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						            <h4 class="modal-title">Delete forever?</h4>
						        </div>
						        <div class="modal-body">
						            <p>Are you sure you'd like to delete this message(s) permanently?</p>
						        </div>
						        <div class="modal-footer">
						            <button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>
						            <button class="btn btn-danger" onclick="triggerMessageListDelete(this); return false;" data-redirect="{!! Request::url() !!}">Delete Message(s)</button>
						        </div>
						    </div>
						</div>
					</div>	

		        </div>

		    </div>			
		</div>
	</div>
</div>
<div class="mail-box">

	@if(isset($type) && $type == 'new-leads' && isset($thread_count) && $thread_count < 1)
		<div class="ibox-content">
			<div class="alert alert-warning">You currently have no new prospects.</div>
		</div>
	@endif

	{!! $tbl->render() !!}

</div>