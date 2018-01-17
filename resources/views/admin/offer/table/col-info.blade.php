<div class="row">
	<div class="col-xs-12">
		<label class="label label-{!! config('lbt.offer.author_type.' . $offer->author_type . '.badge') !!}">{!! config('lbt.offer.author_type.' . $offer->author_type . '.label') !!}</label>
		@if($offer->status == 'draft')
			<span class="text-danger">{!! $offer->humanStatus() !!}</span>
		@else
			<span class="text-black">{!! $offer->humanStatus() !!}</span>
		@endif
	</div>
	<div class="col-xs-12">
		<h3 class="text-navy">{!! $offer->title !!}</h3>
	</div>
</div>