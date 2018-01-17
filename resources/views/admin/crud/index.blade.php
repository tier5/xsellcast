@extends('admin.layout.admin-main')

@section('htmlheader_title')
	{!! $layout['page_title'] !!}
@endsection

@section('right_extra')
	<div class="text-center">
		@if(isset($layout['extra']['page_btn']))
			@foreach($layout['extra']['page_btn'] as $btn)
				<a href="{!! $btn['url'] !!}" @if(!isset($btn['attr']))class="btn btn-default"@endif @foreach($btn['attr'] as $k => $v ) {!! $k !!}="{!! $v !!}" @endforeach>@if(isset($btn['attr']['icon_class']))<i class="{!! $btn['attr']['icon_class'] !!}"></i> @endif{!! $btn['label'] !!}</a>
			@endforeach
		@endif
	</div>
@endsection

@section('content')

    <div class="row">
    	@if($columns->getViews())
        @foreach($columns->getViews() as $view)
            {!! $view->render() !!}
        @endforeach
        @endif
    </div>

@endsection