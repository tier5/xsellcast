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
				Your password has been successfully reset. You can login <a href="{!! route('auth.login') !!}" target="_blank">here</a>.<br/>
                <br/>
				Thank you,<br/>
				Xsellcast Support
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