@extends('admin.layout.plane')

@section('htmlheader_title')
Password recovery
@endsection

@section('content')
<h3>Password Recovery</h3>

<form class="m-t" role="form" method="post" action="{{ route('forgotpassword.post') }}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="form-group has-feedback">
        <input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}"/>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
    </div>

    <div class="form-group text-center">
        <button type="submit" class="btn btn-primary btn-block btn-flat">Send Password Reset Link</button>
    </div>

    <div class="form-group text-center">
        <a class="btn btn-white btn-block" href="{{ url('/auth/login') }}">Log in</a>
        <a class="btn btn-white btn-block" href="{{ url('/auth/register') }}" class="text-center">Register a new membership</a>
    </div>                    
</form>

@endsection
