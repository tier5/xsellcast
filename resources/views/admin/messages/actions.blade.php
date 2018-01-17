<div class="row">
	<div class="col-lg-4 col-md-6 col-sm-7 m-b-md">
		{!! Form::open(['url' => Request::url(), 'method' => 'get', 'style' => 'position: relative; z-index: 0;']) !!}
		<div class="input-group">
			<input type="text" class="form-control" name="s" value="{!! Request::get('s') !!}" /> 
			<span class="input-group-btn"> 
				<button type="submit" class="btn btn-primary">Search</button>
			</span>
		</div>	
		{!! Form::close() !!}	
	</div>

    <div class="hidden-xs col-sm-4 col-md-3 col-lg-2 col-sm-offset-1 col-md-offset-3 col-lg-offset-6">
        <a class="btn btn-primary btn-block" href="{!! route('admin.messages.create') !!}"><i class="fa fa-plus"></i> New Message</a>
    </div>
</div>