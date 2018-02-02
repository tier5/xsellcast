@foreach($list as $k => $v)
	<div class="radio i-checks"><label> <input type="radio" value="{!! $k !!}" name="{!! $name !!}" @if($value == $k)checked="checked"@endif> <i></i> {!! $v !!}</label></div>
@endforeach