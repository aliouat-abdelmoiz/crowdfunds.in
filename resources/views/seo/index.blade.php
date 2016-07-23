@extends('app')
@section('title')
    Jobs in {{ $city or 'by' }} {{ $state or '' }} {{ $country or '' }}
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
            <script type="application/ld+json">
                        {
                          "@context": "http://schema.org/",
                          "@type": "Job",
                          "name": "{{ $item }}"
                        }
                        </script>
        @endforeach
    </ul>
@endsection