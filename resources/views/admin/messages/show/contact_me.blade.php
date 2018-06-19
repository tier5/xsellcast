@extends('admin.messages.show.parts.layout')

@section('message_content_body')

    <div class="mail-body">
        Hello <strong>{!! $user->firstname !!}</strong>,<br/>
        <br/>
        <strong>{!! $talking_to->firstname !!}</strong> has requested that you call them
        @if($thread->getMeta('phone_number') && $thread->getMeta('phone_number') != '')
            at <strong>{!! $thread->getMeta('phone_number') !!}</strong>
        @elseif($talking_to->customer->available_phone)
            at <strong>{!! $talking_to->customer->available_phone !!}</strong>
        @endif
         regarding the following item seen on LuxuryBuysToday.com:
    </div>

    @if(isset($offer) && $offer)
    @include('admin.messages.show.parts.offer-info')
    @endif
      @if(isset($cta_brand))
    @include('admin.messages.show.parts.brand-info')
    @endif
    @include('admin.messages.show.parts.messages')

    <div class="mail-body text-right tooltip-demo">
        @include('admin.messages.show.parts.buttons')
    </div>
    <div class="clearfix"></div>

@endsection

@section('message_content_header')
    <div class="pull-right tooltip-demo">
       @include('admin.messages.show.parts.buttons')
    </div>
    @include('admin.messages.show.parts.message-info')
@endsection