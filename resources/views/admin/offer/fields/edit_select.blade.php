<div class="form-group edit_select">
	<label for="--" class="control-label" style="display: block">&nbsp;</label>
	<div class="the-field">
		<label>{!! $crud_field->getOption('label') !!}</label>
		<label class="selected" for="{!! $crud_field->getOption('name') !!}">
			<span class="txt">{!! $crud_field->getOption('list')[$crud_field->getOption('value')] !!}</span>
			{!! Form::select($crud_field->getOption('name') , 
				$crud_field->getOption('list'), $crud_field->getOption('value'), ['id' => $crud_field->getOption('name')]); !!}
			<span class="text-success">Edit</span>
		</label>
	</div>
</div>