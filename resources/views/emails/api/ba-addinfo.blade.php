@extends('beautymail::templates.minty')

@section('content')

    @include('emails.minty.contentStart', ['bgcolor' => '#000'])
        <tr>
            <td>
                Hi ,
            </td>
        </tr>
        <tr>
            <td width="100%" height="10"></td>
        </tr>
        <tr>
            <td class="paragraph">
{{-- {{dd($customer->user)}} --}}
                {{$customer->user->firstname}} has requested that you contact them regarding <a>{{$offer->title}}</a>.  Please contact this prospect within 24 hours. {{$customer->user->firstname}}, {{$customer->cellphone}}, {{$customer->user->email}}”

                <br/>
				<br/>
				Thank you,<br/>
				www.luxurybuystoday.com Support
            </td>
        </tr>
        <tr>
            <td width="100%" height="25"></td>
        </tr>
        <tr>
            <td width="100%" height="10"></td>
        </tr>
        <tr>
            <td class="paragraph">

            </td>
        </tr>
        <tr>
            <td width="100%" height="25"></td>
        </tr>
        <tr>
            <td width="100%" height="25"></td>
        </tr>
    @include('emails.minty.contentEnd')

@stop