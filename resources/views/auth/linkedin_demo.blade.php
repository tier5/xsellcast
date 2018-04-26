@extends('admin.layout.plane')

@section('htmlheader_title')
Linkedin Demo
@endsection


@section('content')
<table border="1" class="table table-bordered">
    <tr>
        <th>Field</th>
        <th>Value</th>
    </tr>
@foreach($user->user as $key => $details)
    @if(!in_array($key, ['pictureUrls']))
    <tr>
        <th>{{$key}}</th>
        <td class="text-left">
        @if($key=='location')
        {{$details['name']}}
        @else



        @if (filter_var($details, FILTER_VALIDATE_URL))
             <a href="{{$details}}" target="_blank">{{$details}}</a>
        @else
            {{$details}}
        @endif
        @endif
        </td>
    </tr>
    @else

    @endif
@endforeach
</table>





    </div>

@endsection
