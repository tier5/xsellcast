<button type="button" class="btn {!! $crud_field->getOption('class') !!}" data-toggle="modal" data-target="#modal_{!! $crud_field->getOption('name') !!}">{!! $crud_field->getOption('label') !!}</button>
<div class="modal inmodal" id="modal_{!! $crud_field->getOption('name') !!}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                 <h4 class="modal-title">Question</h4>
            </div>
            <div class="modal-body">
                <p>{!! $crud_field->getOption('question') !!}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>
                <a href="{!! $crud_field->getOption('url') !!}" class="btn {!! $crud_field->getOption('class') !!}">Yes</a>
            </div>
        </div>
    </div>
</div>