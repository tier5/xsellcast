@extends('partials.blankmain')

@section('body')		
	<div class="container">
		<div class="content">
		<?php // dd(Session::all()); //dd(Session::get('errors')); ?>
			<div class="title">LBT - XSellCast</div>
			<div class="links"><a href="{{ route('home') }}">Go to Admin</a></div>
		</div>
	</div>
@endsection