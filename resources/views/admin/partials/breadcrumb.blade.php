<ol class="breadcrumb">
	@foreach($items as $item)
	  <li@lm-attrs($item) @if($item->hasChildren())class ="dropdown"@endif @lm-endattrs>
	  	@if($item->isActive)
	  		<strong>{!! Html::link($item->url(), $item->title) !!}</strong>
	  	@else
	  		@if($item->url() != URL::to('/'))
	  			{!! Html::link($item->url(), $item->title) !!}
	  		@else
	  			{!! $item->title !!}
	  		@endif
	    @endif
	  </li>
	@endforeach
</ol>