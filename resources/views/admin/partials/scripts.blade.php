<!-- Mainly scripts -->
<script src="{{ asset('/js/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('/js/app.js') }}"></script>

@yield('scripts')

@include('admin.partials.mustache.offer-select-field-item')
@include('admin.partials.mustache.modal-okay')
@include('admin.partials.mustache.category-delete-modal')
@include('admin.partials.mustache.category_edit_form_modal')

<script type="text/javascript">

</script>