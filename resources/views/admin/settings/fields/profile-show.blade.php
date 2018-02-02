<div class="form-group">
    <div class="ibox-content ibox-field">
    	@foreach($crud_field->getOption('list') as $key => $val)
        <label class="checkbox-inline"> 
        	<input type="checkbox" name="{!! $crud_field->getOption('name') !!}[]" value="{!! $key !!}" class="i-checks" 
@if(isset($crud_field->getOption('value')[$key]) && $crud_field->getOption('value')[$key] > 0){!! 'checked="checked"' !!}@endif /> {!! $val !!}
        </label>
        @endforeach
    </div>
</div>