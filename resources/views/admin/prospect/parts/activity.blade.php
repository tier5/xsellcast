<div class="ibox-content ibox-heading">
    <h3>Activity</h3>
</div>

<div class="no-padding">

	{!! HTML::adminProspectActRow(HTML::link(route('admin.prospects.activity', [null, 'c' => $customer['id']]), 'Activity Feed'), $customer['activity_count'] . ' items'); !!}
	
    {!! HTML::adminProspectActRow('Activity Score', HTML::progressBar(rand(0, 100))); !!}

    @if($customer['last_activity_human'])
    {!! HTML::adminProspectActRow('Last Activity', $customer['last_activity_human']->diffForHumans()); !!}
    @endif
    
    {!! HTML::adminProspectActRow('Date joined', $customer['date_joined']->diffForHumans()); !!}

    {!! HTML::adminProspectActRow(HTML::link(route('admin.prospects.offers', [ 'customer_id' => $customer['id'] ]), 'Lookbook'), $customer['lookbook_count']); !!}

    {!! HTML::adminProspectActRow('Appointments', '0'); !!}
    
</div>