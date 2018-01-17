<div class="field-hours-of-operations">
	<label>{!! $crud_field->getOption('label') !!}</label>
	<table class="table table-bordered">
		@foreach(['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'] as $day)
		<tr>
			<td style="vertical-align: middle">{!! ucfirst($day) !!}</td>
			<td class="text-center" style="vertical-align: middle">
				<div class="row hours">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 dash-after">
						{!! Form::plain_hours($crud_field->getOption('name') . "[$day][from]", (isset($value[$day]['from']) ? $value[$day]['from'] : null)) !!}
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
						{!! Form::plain_hours($crud_field->getOption('name') . "[$day][to]", (isset($value[$day]['to']) ? $value[$day]['to'] : null)) !!}		
					</div>
				</div>
			</td>
			<td style="vertical-align: middle">
				<div>
					<div class="row">
					    <div class="col-lg-offset-2 col-lg-10">
					        <div class="i-checks"><label> <input type="checkbox" value="1" name="{!! $crud_field->getOption('name') !!}[{!! $day !!}][closed]" onchange="officeHoursFieldSetCheck(this)" @if(isset($value[$day]['closed'])){!! 'checked="checked"' !!}@endif><i></i> Closed </label></div>
					    </div>
				    </div>
				</div>
			</td>
		</tr>
		@endforeach
	</table>
</div>