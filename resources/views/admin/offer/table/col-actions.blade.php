<div class="text-right">
	@if($offer->isEditable())
	<a href="{!! route('admin.offers.update', ['offer_id' => $offer->id]) !!}" class="btn btn-sm btn-white"><i class="fa fa-pencil"></i> Edit</a>
	@endif
	@if($offer->isDeletable() || $is_csr)
	<a href="#" class="btn btn-sm btn-white" data-toggle="modal" data-target="#{!! 'offer_modal_' . $offer->id !!}"><i class="fa fa-trash"></i> Delete</a>
	@endif
	<a href="#" class="btn btn-sm btn-white" data-toggle="modal" data-target="#{!! 'offer_modal_share_' . $offer->id !!}"><i class="fa fa-share-alt"></i> Share</a>
	<a href="{!! route('lbt.offer', ['wp_post_id' => $offer->wpid]) !!}" class="btn btn-sm btn-white" target="_blank"><i class="fa fa-share-square-o"></i> See on LBT</a>
	{!! Html::modalYesNo('Are you sure you would like to delete this offer?', 'offer_modal_' . $offer->id, ['yes_url' => route('admin.offers.destroy', ['offer_id' => $offer->id]), 'yes_class' => 'btn-danger']) !!}
</div>

<div class="modal" id="{!! 'offer_modal_share_' . $offer->id !!}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">  
        <div class="modal-content animated bounceInRight">
			<div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
			    <h4 class="modal-title">Compose new message</h4>
			</div>

            {!! App\Storage\Messenger\MessageNewCrud::formForModal(['subject' => "I thought you'd like this offer", 'pre_id' => $offer->id])->getForm()->render(); !!}
        </div>
    </div>
</div>