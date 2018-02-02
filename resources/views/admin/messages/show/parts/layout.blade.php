<div class="animated fadeInRight">
    <div class="mail-box-header">
    	@yield('message_content_header')
    </div>
    <div class="mail-box">

    	@yield('message_content_body')

    	<div id="messageReplyBox" class="collapse">
    		{!! Form::open(['method' => 'POST', 'url' => route('admin.messages.reply', ['thread_id' => $thread->id])]) !!}
		    <div class="mail-body">
		    	<div class="row">
		    		<div class="col-md-8 col-md-offset-2">
		    			<textarea class="tinymce-field" name="message"></textarea>
		    		</div>
		    	</div>
		    </div>

		    <div class="mail-body text-right tooltip-demo">
		    	 <button data-toggle="collapse" data-target="#messageReplyBox" class="btn btn-white">Cancel</button>
		    	<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="Send"><i class="fa fa-reply"></i> Send</button>
		    </div>

		    <input type="hidden" name="redirect_to" value="{!! \Request::fullUrl() !!}" />
		    {!! Form::close(); !!}		
    	</div>
    </div>

</div>