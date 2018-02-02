<div class="ibox-content ibox-heading">
    <div class="row">
        <div class="col-sm-2 text-center">
            @if($offer_thumb)
                <img class="img-responsive" src="{!! $offer->getThumbnail()->getSize(150, 100) !!}" />
            @endif
        </div>
        <div class="col-sm-10">
            <small>Published by @if($brand){!! $brand->name !!}@else{!! 'N/A' !!}@endif on {!! $offer->createdAtDayOrDate() !!}</small>
            <h3><a href="{!! $offer->lbtUrl() !!}" target="_blank">{!! $offer->title !!}</a></h3>
        </div>
    </div>
</div>