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
				Click here to reset your password:<br/>
                <br/>
				<a style="color: #ffffff; background: #1ab394; text-align:center;text-decoration: none;display:block;padding-left:25px; padding-right:25px;background-clip: padding-box;height:36px;line-height: 36px" href="{!! env('LBT_URL').'?token ='. $user->token !!}">Reset Password</a><br/>
				<br/>
				<a href="{!! env('LBT_URL').'?token ='. $user->token !!}">   {!! env('LBT_URL').'?token ='. $user->token !!}  </a><br/>
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