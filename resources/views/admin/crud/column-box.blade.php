<div class="{!! $class !!}" @if($options['column_id'])id="{!! $options['column_id'] !!}"@endif>
    @if($options['type'] == 'form')
        {!! $view !!}
    @else

        @if($options['show_box'])

            {!! Html::box_open($box_title) !!}

                {!! Html::box_body_open($options['box_body_class']) !!}

                    {!! $view !!}

                {!! Html::box_body_close() !!}

                @if($options['type'] === 'table')
                    {!! Html::box_footer($options['footer_view']) !!}
                @else
                {!! Html::box_footer($options['footer_view']) !!}
                @endif
            {!! Html::box_close() !!}

        @else

            {!! $view !!}

        @endif
    @endif
</div>