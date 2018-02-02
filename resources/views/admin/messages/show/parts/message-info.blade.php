<h2>View Message</h2>
<div class="mail-tools tooltip-demo m-t-md">
	@if($type && $offer)
    <h3>
        @if($type)<span class="font-noraml">{!! $type['head_name_singular'] !!}: </span>@endif @if($offer){!! $offer->title !!}@endif
    </h3>
    @endif
    <h5>
        <span class="pull-right font-noraml">{!! strtoupper($thread->local_created_at->format('m/d/Y h:i A')) !!}</span>
        <span class="font-noraml">From: </span>{!! $talking_to->email !!}
        <span class="m-r-sm"></span>
        <a href="{!! route('admin.prospects.show', ['customer_id' => $talking_to->customer->id]) !!}" class="btn btn-white"><i class="fa fa-user"></i> Prospect Profile</a>
    </h5>
</div>