@extends('app')
@if(isset($c) and count($c) > 0)
@section('title')
    {{ $c->name }} - Your Service Connection
@endsection
@section('description')
    {{ $c->description }}
@endsection
@section('canonical')
    {{ $c->title }}
@endsection
@section('keywords')
    {{ $c->keywords }}
@endsection
@endif
@section('content')
    <a href="/">Home</a>
{!! $categories->render() !!}
<section class="row add-margin">
    <article class="col-md-9 no-margin pages">
        <h4 class="heading">Site Categories</h4>
        @foreach(array_chunk($categories->getCollection()->all(), 3) as $subcat)
            <div class="row">
                @foreach($subcat as $subcategory)
                    <article class="col-md-4 image-list add-margin add-border-bottom pageitem">
                        <a href="/jobs/{{ \App\Category::find($subcategory->category->id)->name }}/{{ $subcategory->name }} }}">
                            <div class="overlay-link">Send Job</div>
                            <img class="thumbimg" onerror="imgError(this)" src="/images/uploads/thumbs/{{ $subcategory->image }} " alt=""/>
                        </a>
                        <p><small>{{ $subcategory->name }}</small>
                        <small class="sub-text">{{ str_limit($subcategory->tags, 50) }}</small>
                        </p>
                    </article>
                    <script type="application/ld+json">
                        {
                          "@context": "http://schema.org/",
                          "@type": "Product",
                          "name": "{{ $subcategory->name }}",
                          "image": "https://admin.yourserviceconnection.com/upload/subcategories/images/thumbs/{{ $subcategory->image }}",
                          "description": "{{ $subcategory->title }}"
                        }
                        </script>
                @endforeach
            </div>
        @endforeach
    </article>
    <article class="col-md-3 advertise">
        <h4>Advertise</h4>
    </article>
</section>

@stop
