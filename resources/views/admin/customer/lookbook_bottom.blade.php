<div class="m-b-md hidden-xs">
	<div class="text-right">
		<a href="{!! route('admin.prospects.show', ['customer_id' => $customer->id]) !!}" class="btn btn-primary"><i class="fa fa-chevron-left"></i> Return to Prospect</a>
	</div>	
</div>

@section('before_nav')
	@parent

	<div id="offer_listing_bottom" class="text-right visible-xs">
		<a href="{!! route('admin.prospects.show', ['customer_id' => $customer->id]) !!}" class="btn btn-primary"><i class="fa fa-chevron-left"></i> Return to Prospect</a>
	</div>	
@endsection