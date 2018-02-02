<div class="modal" id="{!! $id !!}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">  
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Question</h4>
            </div>
            <div class="modal-body">
                <p>{!! $question !!}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">{!! $options['no_label'] !!}</button>
                <a type="button" class="btn @if(isset($options['yes_class'])){!! $options['yes_class'] !!}@else{!! 'btn-primary' !!}@endif btn-primary" href="{!! $options['yes_url'] !!}">{!! $options['yes_label'] !!}</a>
            </div>
        </div>
    </div>
</div>