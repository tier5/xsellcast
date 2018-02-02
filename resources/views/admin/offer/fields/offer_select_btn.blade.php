<div class="form-group">
	<button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#addLBTOfferModal">Add LBT Offer</button> 
</div>	

<div class="modal" id="addLBTOfferModal" tabindex="-1" role="dialog" aria-hidden="true" data-name="{!! $crud_field->getOption('name') !!}" data-select-event="{!! $crud_field->getOption('select_event') !!}">
    <div class="modal-dialog">
    	<div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Choose an Offer</h4>
            </div>
            <div class="inmodal">
	            <div class="modal-body no-padding">
	                <table class="offer_table table table-striped table-bordered">
	                	<tbody></tbody>
	                </table>
	                <div class="text-center">
	                	<button class="btn btn-default btn-lg load-more m-b-md" onclick="offerSelectFieldClick(this)">Load More</button>
	                </div>
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>
	                <button type="button" class="btn btn-primary">Select Offer</button>
	            </div>            	
            </div>
        </div>
    </div>
</div>