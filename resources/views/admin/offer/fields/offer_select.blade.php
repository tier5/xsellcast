<label class="">{!! $crud_field->getOption('label') !!}</label>
<div class="offer_select">
	<span class="selected_title @if($crud_field->getOption('offer')) active @endif">
		@if($crud_field->getOption('offer'))
		{!! $crud_field->getOption('offer')->title !!}
		@endif
	</span>
	<button type="button" class="btn btn-danger @if(!$crud_field->getOption('offer')){!! 'hidden' !!}@endif" onclick="offerSelectItemRemoves(this)" title="Remove offer"><i class="fa fa-close"></i></button>
	<input type="hidden" value="{!! $crud_field->getOption('value') !!}" name="{!! $crud_field->getOption('name') !!}" id="{!! $crud_field->getOption('name') !!}" />
	{!! HTML::bs_row(HTML::bs_col($btn, ['xs' => 6, 'sm' => 6, 'md' => 3, 'lg' => 4], ['xs' => 3, 'sm' => 3, 'md' => 0])) !!}
</div>