@if($type == 'offer_request_appt')
    @if($request_activity->get('offer'))
        Sent appointment request for {!! Html::link(config('lbt.wp_site') . '?p=' . $request_activity->get('offer')->wpid, $request_activity->get('offer')->title, ['target' => "_blank"]) !!}.
    @endif
@elseif($type == 'offer_request_price')
    @if($request_activity->get('offer'))
        Sent price request for {!! Html::link(config('lbt.wp_site') . '?p=' . $request_activity->get('offer')->wpid, $request_activity->get('offer')->title, ['target' => "_blank"]) !!}.
    @endif
@elseif($type == 'offer_request_info')
    @if($request_activity->get('offer'))
        Sent more information request for {!! Html::link(config('lbt.wp_site') . '?p=' . $request_activity->get('offer')->wpid, $request_activity->get('offer')->title, ['target' => "_blank"]) !!}.
    @endif
@elseif($type == 'offer_request_contact_me')
    @if($request_activity->get('offer'))
        Sent contact request for {!! Html::link(config('lbt.wp_site') . '?p=' . $request_activity->get('offer')->wpid, $request_activity->get('offer')->title, ['target' => "_blank"]) !!}.
    @endif
@elseif($type == 'added_offer')
    Added offer to lookbook {!! Html::link(config('lbt.wp_site') . '?p=' . $request_activity->get('offer')->wpid, $request_activity->get('offer')->title, ['target' => "_blank"]) !!}
@elseif($type == 'removed_offer')
    Removed offer from lookbook

     {!! Html::link(config('lbt.wp_site') . '?p=' . $request_activity->get('offer')->wpid, $request_activity->get('offer')->title, ['target' => "_blank"]) !!}

@elseif($type == 'direct_message')
    Sent a direct message.
@else
    No configuration for activity "{!! $type !!}"".
@endif