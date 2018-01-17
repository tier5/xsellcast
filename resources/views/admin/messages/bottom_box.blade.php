@section('before_nav')
	@parent

	<div class="text-right visible-xs page_bottom_bar">
		<div class="row">
			<div class="col-xs-6 text-left">
				<a href="{!! route('admin.messages.sent') !!}" class="btn btn-primary">Sent Messages</a>
			</div>
			<div class="col-xs-6 text-right">
				<a href="{!! route('admin.messages.create') !!}" class="btn btn-primary"><i class="fa fa-plus"></i> New message</a>
			</div>
		</div>
	</div>	
@endsection