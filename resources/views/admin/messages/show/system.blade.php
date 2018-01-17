@extends('admin.messages.show.parts.layout')

@section('message_content_header')
    <div class="pull-right tooltip-demo">
      
    </div>
    <h2>View Message</h2>
    <div class="mail-tools tooltip-demo m-t-md">
        <h3>
            <span class="font-noraml">Subject: {!! $type['head_name_singular'] !!} </span> {!! $thread->subject !!}
        </h3>
        <h5>

            <span class="pull-right font-noraml">{!! strtoupper($messages->first()->local_created_at->format('m/d/Y h:i A')) !!}</span>
            <span class="font-noraml">From: </span>Xsellcast Support Team
            <span class="m-r-sm"></span>

            @if($talking_to && $talking_to->hasRole('customer'))
                <a href="{!! route('admin.prospects.show', ['customer_id' => $talking_to->customer->id]) !!}" class="btn btn-white"><i class="fa fa-user"></i> Prospect Profile</a>
            @endif
        </h5>
    </div>
@endsection

@section('message_content_body')
	<div class="mail-body">

		Welcome to Xsellcast! Here are some tips to get started.<br/>
		<br/>
		1. View your first prospect.<br/>
		Click "PROSPECTS" in the left menu. Your prospects that have been matched with you are found in "ALL PROSPECTS" and as you are matched with new names you'll find those under "NEW PROSPECTS". Click on "ALL PROSPECTS" and then on a name to view the prospect's details.<br/>
		<br/>
		2. Respond to your prospect's request.<br/>
		Click "MESSAGES" in the left menu. Filters are available to see which type of requests are sent your way. Select "ALL MESSAGES" and then on a message subject to view the message and respond.<br/>
		<br/>
		Welcome aboard!<br/>
		The Xsellcast Support Team    

	</div>

    <div class="clearfix"></div>

@endsection