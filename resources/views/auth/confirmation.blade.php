@extends('admin.layout.plane')

@section('htmlheader_title')
Email Account Confirmation
@endsection

@section('content')
<h3>Email Account Confirmation</h3>

@if($deleted)
    <p>Account has been confirmed.</p>
@else
    <p>Token don't exists or account has been confirmed already.</p>
@endif  
<a class="btn btn-white btn-block" href="{{ route('auth.login') }}" class="text-center">Log In</a>

@endsection
