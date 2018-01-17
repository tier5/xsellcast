<div class="activity-stream">
    @foreach($activities['data'] as $activity)
        <div class="stream">
            <div class="stream-badge">
                <i class="fa {!! config('lbt.user_action_types.' . $activity['type'] . '.icon') !!}"></i>
            </div>
            <div class="stream-panel">
                <div class="stream-info">
                    <a href="{!! route('admin.prospects.show', ['customer_id' => $activity['user']->customer->id]) !!}">
                        @if($activity['user_avatar'])
                            <img src="{!! $activity['user_avatar'] !!}">
                        @else
                            <img src="{!! URL::to('/') !!}/img/blank-avatar.jpg">
                        @endif
                        <span>{!! $activity['user']->firstname !!} {!! $activity['user']->lastname !!}</span>
                        <span class="date">{!! $activity['created_at_human'] !!}</span>
                    </a>
                </div>
                @include('admin.prospect.activity-content', [
                    'type'             => $activity['type'], 
                    'request_activity' => $activity['request_activity']])
            </div>
        </div>
    @endforeach
</div>