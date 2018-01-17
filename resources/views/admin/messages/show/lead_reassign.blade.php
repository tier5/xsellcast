@extends('admin.messages.show.parts.layout')

@section('message_content_body')

    <div class="mail-body">
        @if($thread->getMeta('is_assign_to_other'))
            A prospect, <strong>{!! $talking_to->firstname !!} {!! $talking_to->lastname !!}</strong>
            , has been assigned to you. Please note that this prospect had previously been assigned to a different Brand Associate and may have already received some level of service from that associate. This prospect is yours to assist from this point forward.<br/>
            <br/>
            Thank you,<br/>
            The Xsellcast Team            
        @else
        <!--
            A prospect that had been assigned to you, <strong>{!! $talking_to->firstname !!} {!! $talking_to->lastname !!}</strong>, was reassigned to a different Brand Associate. Xsellcast strives to provide quick responses to prospect inquiries. We have reassigned this particular prospect to ensure that our commitment to timely service will be met. In the future, please try to accept a new lead and respond to their inquiry within 24 hours of receiving their request.<br/>
            <br/>
            Thank you,<br/>
            The Xsellcast Team
            -->
            A prospect that had been assigned to you, <strong>{!! $talking_to->firstname !!} {!! $talking_to->lastname !!}</strong>, was reassigned to a different Brand Associate.<br/> 
            <br/>
            Thank you,<br/>
            The Xsellcast Team            
        @endif

    </div>

    <div class="mail-body text-right tooltip-demo">
        @include('admin.messages.show.parts.newlead-buttons')
    </div>
    <div class="clearfix"></div>

@endsection

@section('message_content_header')
    <div class="pull-right tooltip-demo">
        @include('admin.messages.show.parts.newlead-buttons')
    </div>
    @include('admin.messages.show.parts.details-reassignment')
@endsection