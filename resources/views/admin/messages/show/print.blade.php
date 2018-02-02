<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title> LBT XSellCast</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('/css/font-awesome.css') }}" rel="stylesheet">

        <!-- Theme style -->
        <link href="{{ asset('/css/admin-style.css') }}" rel="stylesheet" type="text/css" />
    </head>

    <body class="pageprint">
        
        <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="mail-box-header" style="padding-top: 10px">
                        <div class="mail-tools tooltip-demo m-t-md">
                            <h3>
                                <span class="font-noraml">{!! $type['head_name_singular'] !!}: </span> {!! $thread->subject !!}
                            </h3>
                            <h5>
                                <span class="pull-right font-noraml">{!! strtoupper($thread->local_created_at->format('m/d/Y h:i A')) !!}</span>
                                <span class="font-noraml">@if($isFromMe){!! 'To' !!}@else{!! 'From' !!}@endif: </span>{!! $talking_to->email !!}
                                <span class="m-r-sm"></span>

                                @if($talking_to->hasRole('customer'))
                                    <a href="{!! route('admin.prospects.show', ['customer_id' => $talking_to->customer->id]) !!}" class="btn btn-white"><i class="fa fa-user"></i> Prospect Profile</a>
                                @endif
                            </h5>
                        </div>
                    </div>
                    <div class="mail-box">
                        <div class="ibox-content ibox-heading">
                            <div class="row">

                                @if($offerThumb)
                                <div class="col-sm-2 text-center">
                                    <img class="img-responsive" src="{!! $offerThumb->getSize(150, 100) !!}">
                                </div>
                                @endif
                                 <div class="col-sm-10">
                                    <small>Published by @if($brand){!! $brand->name !!}@else{!! 'N/A' !!}@endif on {!! $offer->createdAtDayOrDate() !!}</small>
                                    <h3><a href="{!! $offer->lbtUrl() !!}" target="_blank">{!! $offer->title !!}</a></h3>
                                </div>
                            </div>
                        </div>   

                        <div class="mail-body">
                            @if($message->user->id == $user->id)
                                <strong>My message</strong>
                            @else
                                <strong>{!! $message->user->firstname !!} {!! $message->user->lastname !!}</strong> added this message
                            @endif
                            <br>
                            <br>
                            {!! $message->body !!}
                        </div>                                             
                    </div>
                </div>
            </div>
        </div>

    </body>

</html>