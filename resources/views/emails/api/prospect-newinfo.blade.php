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

                That you for expressing your interested in <a>{{$offer->title}}</a>.  If a brand associate does not contact you within 24 hours, please reach out to them directly using the following information: {{$ba->user->firstname}}, {{$ba->cellphone}}, {{$ba->user->email}}

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