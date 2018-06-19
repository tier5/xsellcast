@if($cta_brand)
    <div class="ibox-content ibox-heading">
        <div class="row">
            @if($cta_brand->image_link!='')
            <div class="col-sm-2 text-center">
                <img class="img-responsive" src="{!! $brand->image_link !!}" />
            </div>
             @endif
            <div class="col-sm-10">

                <h3><a href="#" target="_blank">{!! $cta_brand->name !!}</a></h3>
            </div>
        </div>
    </div>
@endif