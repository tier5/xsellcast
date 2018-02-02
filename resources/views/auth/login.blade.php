@extends('admin.layout.plane')

@section('htmlheader_title')
Log in
@endsection

@section('authbox_class')
loginscreen
@endsection

@section('content')
    <h3>Log In</h3>
    <form class="m-t" role="form" method="post" action="{{ route('login.post') }}">
        <div class="form-group">
            <input type="email" name="email" class="form-control" placeholder="Username" required="required">
        </div>
        <div class="form-group">
            <input type="password" name="password" class="form-control" placeholder="Password" required="required">
        </div>
        <div class="form-group text-right">
            <a href="{{ route('forgotpassword') }}"><small>Forgot password?</small></a>
        </div>
        <button type="submit" class="btn btn-primary block full-width m-b">Login</button>
        <p class="text-muted text-center"><small>Or log in with:</small></p>
        <div class="form-group text-center">
            <a href="{{ route('auth.social.fb') }}" class="btn btn-success btn-facebook btn-outline btn-block">
                <i class="fa fa-facebook"> </i> Sign in with Facebook
            </a>
        </div>
        <p class="text-muted text-center"><small>Don't have an account yet?</small></p>
        <a class="btn btn-white btn-block" href="{{ route('register') }}">Sign Up</a>
    </form>
@endsection
