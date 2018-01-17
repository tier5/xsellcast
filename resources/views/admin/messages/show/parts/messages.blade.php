@foreach($messages as $message)
    @if($message->body != '')
    <div class="mail-body">
        @if($message->user->id == $user->id)
            <strong>My message</strong>
        @else
            <strong>{!! $message->user->firstname !!} {!! $message->user->lastname !!}</strong> added this message
        @endif
        <br/>
        <br/>
        {!! $message->body !!}

        @if($message->media)
            <div class="lightBoxGallery">
            @foreach($message->media as $media)
                <a href="{!! $media->getOrigUrl() !!}">{!! Html::image($media->getSize(150, 100), '', ['width' => 80]) !!}</a>
            @endforeach
            </div>
        @endif
    </div>
    @endif
@endforeach