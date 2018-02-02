<div class="btn-letter-paginate">
	@foreach (range('A', 'Z') as $let)
		<a href="#" class="btn btn-white" data-letter="{!! $let !!}">{!! $let !!}</a>
	@endforeach
</div>