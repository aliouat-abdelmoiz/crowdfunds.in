@extends('app')
@section('content')
    <section class="container">
        @if($errors->any())
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                {{ Form::token() }}
            @endforeach
        @endif

        {!! Form::open(['url' => 'profile', 'files' => true]) !!}
            <article class="row">
                @include('partial.profile.form');
            </article>
        {!! Form::close() !!}
    </section>
@endsection