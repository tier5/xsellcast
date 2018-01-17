<script id="mustache_ba_assignment_item" type="x-tmpl-mustache">

    <div class="faq-item ba-assignment-item has-assign-now-{{brand.salesrep_approve}}-{{brand.salesrep_pending}}" data-key="{{brand.id}}">
        <div class="row">
            <div class="col-md-5">
               {{title}}
            </div>
            <div class="col-md-3 text-center">
            	<span class="hidden text-danger show-pending-{{brand.salesrep_reject}}-{{brand.salesrep_approve}}-{{brand.salesrep_pending}}">pending BA's approval</span>
            </div>
            <div class="col-md-4">
	            <select class="form-control" onfocus="beforeBrandAssignment(this)" onchange="brandAssignment(this)" id="brandfield_{{brand.id}}">
	            <option value="">ASSIGN NOW!</option>
	            {{#salesreps}}
	            	<option value="{{id}}" {{#selected}}selected{{/selected}} data-fullname="{{fullname}}" data-location="{{location}}" >{{name}}</option>
	            {{/salesreps}}
	            </select>
	        </div>
        </div>
    </div>

    <div class="modal brandassignmentModal" id="brandassignment_{{brand.id}}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Question</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you'd like to assign <strong class="ba-name">{{brand.customer.user.firstname}} {{brand.customer.user.lastname}}</strong> as the <strong>{{brand.name}}</strong> Brand Associate to assist this prospect in <strong class="cust-location">{{brand.customer.city}}, {{brand.customer.state}}</strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary btn-yes" onclick="changeProspectBa(this, {{brand.customer.id}})" data-target="#brandassignment_{{brand.id}}" data-salesrep-id="">Yes</a>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {

            $('#brandassignment_{{brand.id}}').on('hidden.bs.modal', function () {
                var oldVal = $('#brandfield_{{brand.id}}').attr('data-old-value');
                $('#brandfield_{{brand.id}} option[value="' + oldVal + '"]').prop('selected', true);

                console.log(oldVal);
            });

        });
    </script>
</script>