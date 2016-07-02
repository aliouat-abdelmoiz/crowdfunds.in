@extends('app')
@section('content')
    <h1>Contact Us</h1>
    <hr>
    {!! Form::open(['url' => '/contact', 'method' => 'post']) !!}
    {!! Form::label("subject", "Subject") !!}
    {!! Form::text('subject', old('subject'), ['class' => 'form-control']) !!}
    <br>
    {!! Form::label("email", "Your Email") !!}
    {!! Form::text('email', old('email'), ['class' => 'form-control']) !!}
    <br>
    {!! Form::label("fullname", "Your Name") !!}
    {!! Form::text('fullname', old('fullname'), ['class' => 'form-control']) !!}
    <br>
    {!! Form::label("message", "Message") !!}
    <textarea name="message" class="form-control" rows="20"></textarea>
    <br>
    {!! Form::submit('Send Message', ['class' => 'btn btn-sm btn-danger']) !!}
    <br>
    <hr>
    {!! Form::close() !!}
@endsection