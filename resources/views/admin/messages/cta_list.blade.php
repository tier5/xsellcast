<div class="mail-box-header">
	<div class="row">
		<div class="col-md-6">
			<h2>@if(isset($boxTitle)){!! $boxTitle !!}@else{!! 'CTA Requests ' !!}@endif ({!! $thread_count !!})</h2>
		</div>
		<div class="col-md-6">
		    <div class="mail-tools tooltip-demo">
		        <div class="btn-group pull-right">
		        	@if((isset($showAction) && $showAction) || !isset($showAction))
				        <a href="{!! url()->current() !!}" class="btn btn-white btn-md" data-toggle="tooltip" data-placement="left" title="Refresh CTA Requests "><i class="fa fa-refresh"></i> Refresh</a>
				        <button class="btn btn-white btn-md hidden" data-toggle="tooltip" data-placement="left" title="Settings"><i class="fa fa-gear"></i> </button>
					@endif



		        </div>

		    </div>
		</div>
	</div>
</div>
<div class="mail-box">
	{!! $tbl->render() !!}
</div>