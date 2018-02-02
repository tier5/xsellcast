@extends('partials.blankmain')

@section('body')        
    <div class="container">
        <div class="content">
            <div class="title">403 Error Page</div>
            <p>
				@if(isset($message))
					{!! $message !!}
				@else
					You don’t have access to this resource
				@endif            	
            </p>
        </div>
    </div>
@endsection