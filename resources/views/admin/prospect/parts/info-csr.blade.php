{!! Form::open(['method' => 'put', 'route' => ['admin.prospects.update', $customer['id']] ]) !!}
    <div class="row">
    	<div class="col-md-6 col-sm-6">
    		<div class="form-group">
    			<label>Name</label>
    			<input value="{!! $customer['firstname'] !!}" name="firstname" class="form-control" placeholder="First name" type="text" />
    		</div>
    	</div>
    	<div class="col-md-6 col-sm-6">
    		<div class="form-group">
                <label>&nbsp;</label>
    			<input value="{!! $customer['lastname'] !!}" name="lastname" class="form-control" placeholder="Last name" type="text" />
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
    			<input class="form-control" type="text" name="email" value="{!! old('email', $customer['email']) !!}" />
    		</div>
    	</div>

    	<div class="col-md-6">
    		{!! Form::admin_text('cellphone', 'Cell Phone', $customer['cellphone']); !!}
    	</div>

        <div class="col-md-6">
            {!! Form::admin_text('homephone', 'Home Phone', $customer['homephone']); !!}
        </div>

        <div class="col-md-6">
            {!! Form::admin_text('officephone', 'Office Phone', $customer['officephone']); !!}
        </div>

        <div class="col-md-12">
            {!! Form::admin_text('address1', 'Address 1', $customer['address1']); !!}
        </div>

        <div class="col-md-12">
            {!! Form::admin_text('address2', 'Address 2', $customer['address2']); !!}
        </div>

        <div class="col-md-6">
            {!! Form::admin_text('city', 'City', $customer['city']); !!}
        </div>

        <div class="col-md-6">
            {!! Form::states('state', 'State', $customer['state']); !!}
        </div>

        <div class="col-md-6">
            {!! Form::admin_text('zip', 'Zip', $customer['zip']); !!}
        </div>

    </div>

    <div class="row hidden">

        <div class="col-md-6">
            {!! Form::admin_text('geo_lat', 'Latitude', $customer['geo_lat']); !!}
        </div>

        <div class="col-md-6">
            {!! Form::admin_text('geo_long', 'Longtitude', $customer['geo_long']); !!}
        </div>

    </div>

    <button type="submit" class="btn btn-primary">Update</button>
{!! Form::close() !!}