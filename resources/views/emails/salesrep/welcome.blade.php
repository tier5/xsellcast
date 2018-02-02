@extends('beautymail::templates.minty')

@section('content')

    @include('emails.minty.contentStart', ['bgcolor' => '#000'])
        <tr>
            <td class="title">
                Welcome {{ $user->firstname }} {{ $user->lastname }}
            </td>
        </tr>
        <tr>
            <td width="100%" height="10"></td>
        </tr>
        <tr>
            <td class="paragraph">
                We have successfully setup your account. Please follow link to confirm your account:
            </td>
        </tr>
        <tr>
        	<td>
        		<a href="{{ route('register.confirm.account', ['token' => $token]) }}">{{ route('register.confirm.account', ['token' => $token]) }}</a>
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
                Cheers!<br/>
                X SellCast Admin
            </td>
        </tr>
        <tr>
            <td width="100%" height="25"></td>
        </tr>
        <tr>
            <td>
                @include('emails.minty.button', ['text' => 'Confirm Account', 'link' => route('register.confirm.account', ['token' => $token])])
            </td>
        </tr>
        <tr>
            <td width="100%" height="25"></td>
        </tr>
    @include('emails.minty.contentEnd')

@stop