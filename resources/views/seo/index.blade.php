@extends('app')
@section('title')
    Jobs near {{ $city or 'by' }} {{ $state or '' }} {{ $country or '' }} - Your Service Connection
@endsection
@section('description')

@endsection
@section('keywords')
    'jobs', 'jobs in {{ $country or 'USA' }}', 'jobs in {{ $city or "City" }}', 'jobs in {{ $state or "State" }}'
@endsection
@section('content')
    <ul class="list-group">
        @foreach($data as $item)
            <li class="list-group-item">{!! $item !!}</li>
        @endforeach
    </ul>
@endsection