@extends('app')
@if(isset($page) and count($page) > 0)
@section('title')
    {{ $page[0]->title }}
@endsection
@section('description')
    {{ $page[0]->description }}
@endsection
@section('canonical')
    {{ trim($page[0]->canonical, " ") }}
@endsection
@section('author')
    {{ $page[0]->author }}
@endsection
@section('keywords')
    {{ $page[0]->keywords }}
@endsection
@endif
@section('content')
    <figure class="page-content">
        <section>
            <h2 class="page-title">{{ $page[0]->title }}</h2>
            <p class="page-content">{!!  $page[0]->content !!}</p>
        </section>
    </figure>
    <script type="application/ld+json">
    {
        "@context": "http://schema.org/",
        "@type": "Page",
        "name": "{{ $page[0]->title }}",
    }
    </script>
@endsection