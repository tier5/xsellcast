@foreach($items as $item)
  @if(!$item->isVisibleForUser())
    @php
      continue;
    @endphp
  @endif

  <li@lm-attrs($item) @if($item->getCountUrl())data-count-url="{!! $item->getCountUrl() !!}"@endif @if($item->hasChildren())class ="dropdown"@endif @lm-endattrs>
    @if($item->link) <a@lm-attrs($item->link) @if($item->hasChildren()) class="dropdown-toggle" data-toggle="dropdown" @endif @lm-endattrs href="{!! $item->url() !!}">
      @if($item->icon_class != '')
        <i class="{!! $item->icon_class !!}"></i>
      @endif

      {!! $item->title !!}
      <span class="label label-info hidden pull-right"></span>  
      @if($item->hasChildren()) <b class="caret"></b> @endif
    </a>
    @else
      {!! $item->title !!}
    @endif
    @if($item->hasChildren())
      <ul class="dropdown-menu">
        @include(config('laravel-menu.views.bootstrap-items'), array('items' => $item->children()))
      </ul> 
    @endif
  </li>
  @if($item->divider)
  	<li{!! Lavary\Menu\Builder::attributes($item->divider) !!}></li>
  @endif
@endforeach