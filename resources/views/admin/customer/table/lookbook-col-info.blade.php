<div class="row">
	<div class="col-xs-6">
		<label class="label label-{!! config('lbt.offer.author_type.' . $offer->author_type . '.badge') !!}">{!! config('lbt.offer.author_type.' . $offer->author_type . '.label') !!}</label>
	</div>
	<!--
	<div class="col-xs-6 text-right">{!! $pivot->created_at->format('l \a\t h:i:s A');  !!}</div>
	-->
	<div class="col-xs-12">
		<h3><a target="blank" href="{!! config('lbt.wp_site') !!}?p={!! $offer->wpid !!}">{!! $offer->title !!}</a></h3>
	</div>
</div>