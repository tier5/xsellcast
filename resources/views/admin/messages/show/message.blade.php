@extends('admin.messages.show.parts.layout')

@section('message_content_header')
    <div class="pull-right tooltip-demo">
       @include('admin.messages.show.parts.buttons')
    </div>
    <h2>View Message</h2>
    <div class="mail-tools tooltip-demo m-t-md">
        <h3>
            <span class="font-noraml">{!! $type['head_name_singular'] !!}: </span> {!! $thread->subject !!}
        </h3>
        <h5>

            <span class="pull-right font-noraml">{!! strtoupper($messages->first()->local_created_at->format('m/d/Y h:i A')) !!}</span>
            <span class="font-noraml">@if($isFromMe){!! 'To' !!}@else{!! 'From' !!}@endif: </span>{!! $talking_to->email !!}
            <span class="m-r-sm"></span>

            @if($talking_to->hasRole('customer'))
                <a href="{!! route('admin.prospects.show', ['customer_id' => $talking_to->customer->id]) !!}" class="btn btn-white"><i class="fa fa-user"></i> Prospect Profile</a>
            @endif
        </h5>
    </div>
@endsection

@section('message_content_body')
    @if($offer)
        @include('admin.messages.show.parts.offer-info')
    @endif

    @include('admin.messages.show.parts.messages')

    <div class="mail-body text-right tooltip-demo">
        @include('admin.messages.show.parts.buttons')
    </div>
    <div class="clearfix"></div>

@endsection