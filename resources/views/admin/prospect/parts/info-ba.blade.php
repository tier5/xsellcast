<div class="row">
    <div class="col-md-6 col-sm-6">
        <div class="form-group">
            <label>Name</label>
            <input value="{!! $customer['firstname'] !!}" name="firstname" class="form-control" placeholder="First name" type="text" disabled="disabled" />
        </div>
    </div>
    <div class="col-md-6 col-sm-6">
        <div class="form-group">
            <label>&nbsp;</label>
            <input value="{!! $customer['lastname'] !!}" name="lastname" class="form-control" placeholder="Last name" type="text" disabled="disabled" />
        </div>
    </div>

    @if(isset($customer['job_title']) && $customer['job_title'] != '')
    <div class="col-md-12">
        <div class="form-group">
            <label>Job title</label>
            <input class="form-control" type="text" value="" />
        </div>
    </div>                
    @endif

    @if(isset($customer['company']) && $customer['company'] != '')
    <div class="col-md-6">
        <div class="form-group">
            <label>Company</label>
            <input class="form-control" type="text" value="{!! $customer['company'] !!}" />
        </div>
    </div>
    @endif

    <div class="col-md-12">
        <div class="form-group">
            <label>Email</label>
            <input class="form-control" type="text" name="email" value="{!! old('email', $customer['email']) !!}" disabled="disabled" />
        </div>
    </div>

    @if($customer['cellphone'] && !empty($customer['cellphone']))
    <div class="col-md-6">
        {!! Form::admin_text('cellphone', 'Cell Phone', $customer['cellphone'], ['disabled' => 'disabled']); !!}
    </div>
    @endif

    @if($customer['homephone'] && !empty($customer['homephone']))
    <div class="col-md-6">
        {!! Form::admin_text('homephone', 'Home Phone', $customer['homephone'], ['disabled' => 'disabled']); !!}
    </div>   
    @endif

    @if($customer['officephone'] && !empty($customer['officephone']))
    <div class="col-md-6">
        {!! Form::admin_text('officephone', 'Office Phone', $customer['officephone'], ['disabled' => 'disabled']); !!}
    </div>
    @endif

    @if($customer['address1'] && !empty($customer['address1']))
    <div class="col-md-12">
        {!! Form::admin_text('address1', 'Address 1', $customer['address1'], ['disabled' => 'disabled']); !!}
    </div>   
    @endif

    @if($customer['address2'] && !empty($customer['address2']))
    <div class="col-md-12">
        {!! Form::admin_text('address2', 'Address 2', $customer['address2'], ['disabled' => 'disabled']); !!}
    </div> 
    @endif

    @if($customer['city'] && !empty($customer['city']))
    <div class="col-md-6">
        {!! Form::admin_text('city', 'City', $customer['city'], ['disabled' => 'disabled']); !!}
    </div>  
    @endif

    @if($customer['state'] && !empty($customer['state']))
    <div class="col-md-6">
        {!! Form::admin_text('state', 'State', $customer['state'], ['disabled' => 'disabled']); !!}
    </div>   
    @endif

    @if($customer['zip'] && !empty($customer['zip']))
    <div class="col-md-6">
        {!! Form::admin_text('zip', 'Zip', $customer['zip'], ['disabled' => 'disabled']); !!}
    </div>   
    @endif
</div>

<div class="row hidden">   

    <div class="col-md-6">
        {!! Form::admin_text('geo_lat', 'Latitude', $customer['geo_lat'], ['disabled' => 'disabled']); !!}
    </div>    

    <div class="col-md-6">
        {!! Form::admin_text('geo_long', 'Longtitude', $customer['geo_long'], ['disabled' => 'disabled']); !!}
    </div>                                                    

</div>