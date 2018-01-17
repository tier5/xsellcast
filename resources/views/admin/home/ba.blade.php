<div class="row">

    <div class="col-md-6">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <a href="{!! route('admin.prospects.leads') !!}" class="pull-right"><span class="label label-danger">Take Action Now</span></a>
                <h5>New Leads</h5>
            </div>
            <div class="ibox-content">
                <a href="{!! route('admin.prospects.leads') !!}"><h1 class="no-margins text-center text-danger">{!! $newLeadCount !!}</h1></a>
                <div class="m-b-md"></div>
                <!--
                <div class="stat-percent font-bold text-success">98% <i class="fa fa-bolt"></i></div>
                <small>Total income</small>
                -->
            </div>
        </div>
    </div>	

    <div class="col-md-6">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>All Prospects</h5>
            </div>
            <div class="ibox-content">
                <a href="{!! route('admin.prospects') !!}" class="text-default"><h1 class="no-margins text-center">{!! $prospectCount !!}</h1></a>
                <div class="m-b-md"></div>
            </div>
        </div>
    </div>	    

</div>