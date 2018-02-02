<div class="form-group" class="field-{!! str_slug($crud_field->getOption('type')) !!}">
	<label for="email" class="control-label" style="display: block">{!! $crud_field->getOption('label') !!}</label>
	<input type="hidden" name="{!! $crud_field->getOption('name') !!}" class="field-value" value="@if(isset($crud_field->getOption('value')[0])){!! $crud_field->getOption('value')[0] !!}@endif" />
	<div class="input-group">
    	<span class="form-control dealer-name">@if(isset($crud_field->getOption('value')[1])){!! $crud_field->getOption('value')[1] !!}@endif</span>
    	<span class="input-group-btn"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-{!! $crud_field->getOption('name') !!}"><i class="fa fa-edit"></i></button></span>
    </div>

	<div class="modal modal-{!! str_slug($crud_field->getOption('type')) !!}" id="modal-{!! $crud_field->getOption('name') !!}" data-field-name="{!! $crud_field->getOption('name') !!}" data-access-token="{!! $crud_field->getOption('access_token') !!}" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog">
	    	<div class="modal-content animated bounceInRight">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	                <h4 class="modal-title">Find My Dealer/Company</h4>
	            </div>
	            <div class="inmodal">
		            <div class="modal-body" >
		            	<div class="form-group">
		            		<input type="text" class="form-control field-zip" placeholder="Company Zip Code..." />
		            	</div>
		            	<div class="form-group">
		            		<div class="input-group">
		            			<select class="form-control field-category"></select>
		            			<span class="input-group-btn"><button type="button" class="btn-submit btn btn-primary"><i class="fa fa-chevron-right"></i></button></span>
		            		</div>
		            	</div>
		            	<div class="form-group">
		            		<p class="result-text"><span class="count"></span> matching results:</p>
		            		<ul class="dealers"></ul>
		            	</div>
		            </div>
		            <div class="modal-footer">
		                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
		            </div>	            	
	            </div>
	        </div>
	    </div>
	</div>    
</div>