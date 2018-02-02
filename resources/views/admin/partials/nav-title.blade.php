<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-md-9">
    	@if(isset($layout['page_title']))
        	<h2>{!! $layout['page_title'] !!}</h2>
        @endif

        @if(isset($layout['breadcrumb']) && $layout['breadcrumb'] && !empty($layout['breadcrumb']))
        	@include('admin.partials.breadcrumb', array('items' => $layout['breadcrumb']->roots()))
        @endif
    </div>

    <div class="col-md-3">
    	<div class="statistic-box">
    		@yield('right_extra')
    	</div>
    </div>
</div>