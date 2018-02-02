<div class="lbt-ajax-table tbl-waypoint" data-url="{!! $collection->getExtra('url') !!}" data-callback-func="{!! $collection->getExtra('js-callback') !!}" data-after-append="{!! $collection->getExtra('after-append') !!}" >

	@if($collection->getExtra('view_before'))
		{!! view($collection->getExtra('view_before'))->render() !!}
	@endif

	@include('admin.crud.table.laravel-5-table')

	<div class="text-center">
		<a href="#" class="btn btn-default btn-lg btn-loadmore">LOAD MORE</a>
	</div>

</div>