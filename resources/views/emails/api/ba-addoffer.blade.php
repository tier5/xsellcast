@extends('beautymail::templates.minty')

@section('content')

    @include('emails.minty.contentStart', ['bgcolor' => '#000'])
        <tr>
            <td>
                Hi {{ $user->firstname }},
            </td>
        </tr>
        <tr>
            <td width="100%" height="10"></td>
        </tr>
        <tr>
            <td class="paragraph">
				New Offer is added to lookbook:<br/>
                <br/>
                <table>
                    <tr>
                        <td>Prospect Name</td>
                        <td>{{$customer->user->firstname}}</td>
                    </tr>
                    <tr>
                        <td>Offer</td>
                        <td>{{$offer->contents}}</td>
                    </tr>
                </table>

				<a href="{!! env('LBT_URL').'?offer=' !!}">   {!! env('LBT_URL').'?offer=' !!}  </a><br/>
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