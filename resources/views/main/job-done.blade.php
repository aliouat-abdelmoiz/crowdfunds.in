@extends('app')
@section('content')
	<div class="done-box">
		<h2>Thanks for Joining</h2>
        <p>Please check your email to continue with your job post.</p>
        <small>Job sent to {{ $count or 0 }} Providers</small>
        <hr>
        <a href="/auth/login">Click here to continue</a>
	</div>
@endsection