<div class="ibox-content ibox-heading">
    <h3>Brand Associate Assignments</h3>
</div>

<div class="no-padding hidden" id="no-request-notice">
    <div class="faq-item">
        <div class="row">
            <div class="col-md-12">
                This prospect has not yet requested pricing, information, or appointments on any offers.
            </div>
        </div>
    </div>
</div>

<div class="no-padding brand-associate-assignment" id="ba_assingment" data-customer-id="{!! $customer['id'] !!}"></div>

@section('scripts')

    @parent

    @include('admin.partials.mustache.mustache_ba_assignment_item')

    <script type="text/javascript">

    var populateBaAssingment = function()
    {
        var custId = $('#ba_assingment').attr('data-customer-id');
        var $content = $('#ba_assingment');

        $.ajax({
            url: laroute.route('admin.api.prospect.ba.assignment', {customer_id: custId}),
            dataType: 'json',
            type: 'get',
            success: function(data) {  
                $.each(data.data, function(k, brand){
                    var template = document.getElementById('mustache_ba_assignment_item').innerHTML;
                    Mustache.parse(template);
                    //has-assign-now-false-true
                    //Render the data into the template
                    var rendered = Mustache.render(template, {brand:brand, title: brand.name_with_loc, salesreps: brand.salesreps});

                    //Overwrite the contents of #target with the rendered HTML
                    $content.append(rendered);                  
                });

                if(data.data.length < 1){
                    $('#no-request-notice').removeClass('hidden');
                }else{
                    prospectShowProfileSetSign();
                }
            }
        }).complete(function(){

        });        
    };

    $(document).ready(function () {
        populateBaAssingment();
    });

    </script>


@endsection