<div class="form-group">
    {!! $label !!}
    @if($help)
	    <div class="input-group">
	        {!! $input !!}
	        <span class="input-group-addon input-help" data-container="body" data-toggle="popover" data-placement="top" data-content="{!! $help !!}" data-original-title="Help" title=""><i class="fa fa-question"></i></span>
	    </div>
    @else
    	{!! $input !!}
    @endif
</div>