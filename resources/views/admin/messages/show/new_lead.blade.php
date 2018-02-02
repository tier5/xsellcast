@extends('admin.messages.show.parts.layout')

@section('message_content_body')

    <div class="mail-body">
        Hello <strong>{!! $user->firstname !!}</strong>,<br/>
        <br/>
		<strong>{!! $talking_to->firstname !!} {!! $talking_to->lastname !!}</strong> has requested more information regarding an offer on LuxuryBuysToday.com. Please accept this new lead to view the specific request and reply to the lead within 24 hours.<br/>
    <br/>
    After accepting this lead, you will see the specific offer request in your Messages.
    </div>
   

   <?php /**
          *@include('admin.messages.show.parts.messages') 
          */ ?>

    <div class="mail-body text-right tooltip-demo">
        @include('admin.messages.show.parts.newlead-buttons')
    </div>
    <div class="clearfix"></div>

@endsection

@section('message_content_header')
    <div class="pull-right tooltip-demo">
       @include('admin.messages.show.parts.newlead-buttons')
    </div>
    @include('admin.messages.show.parts.message-info')
@endsection