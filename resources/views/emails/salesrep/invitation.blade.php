@extends('beautymail::templates.minty')

@section('content')

    @include('emails.minty.contentStart', ['bgcolor' => '#000'])
        <tr>
            <td class="title">
                Hi {{ $user->firstname }},
            </td>
        </tr>
        <tr>
            <td width="100%" height="10"></td>
        </tr>
        <tr>
            <td class="paragraph">
                We are happy to invite you to Xsellcast and look forward to sending you great leads.<br/>
                @if($password)
                <br/>
                <p style="background: #f3f3f4; padding: 20px">
                Temporary password: {!! $password !!}
                </p>
                @endif
                <br/>
                To get started, click the following link:
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