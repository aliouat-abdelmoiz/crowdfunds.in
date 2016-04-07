@extends('user')
@if(Auth::check())
@section('title')
    {{ Auth::user()->provider->name }} - Your Service Connection Provider
@endsection
@endif
@section('main-content')
    <section class="col-md-10">
        @if(Session::has("errors"))
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        @endif
        {!! Form::model($provider,['method' => 'patch', 'action' => ['Profile@update', $provider->id], 'files' => true]) !!}
        @include('partial.profile.form')
        {!! Form::close() !!}
    </section>
@endsection