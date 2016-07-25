@extends('app')
@section('title')
    Your Service Connection - Home Page
@endsection
@section('description')
@endsection
@section('banner')
    <h2 class="title-home">{{ DB::table('personal')->find(1)->title }}</h2>
    <p class="para-home">
        {{ DB::table('personal')->find(1)->text }}
    </p>
    <a href="https://blog.yourserviceconnection.com" class="btn btn-red center-block">See our blog for more
        information</a>
@endsection
@section('content')
    @if(Session::has('status'))
        <p class="alert alert-info add-margin">{{ Session::get('status') }}</p>
    @endif
    {!! $cats->render() !!}
    <section class="row add-margin" id="categories">
        <article class="col-md-3 hidden-lg hidden-md advertise">
            <h4>Advertise</h4>
        </article>
        <article class="col-md-9 no-margin pages">
            <h4 class="heading">Site Categories</h4>
            <ul class="item-new row">
                @foreach(array_chunk($cats->getCollection()->all(), 3) as $cat)
                    @foreach($cat as $category)
                        <li class="col-md-4 pageitem">
                            <a href="/Items/{{$category->name}}">
                                <img id="cat{{ $category->id }}" onerror="imgError(this)"
                                     class="thumbimg no-margin"
                                     src="{{ \App\Category::GetPrimaryUserPic($category->id) == "not" ? asset('images/no.gif') : \App\Category::GetPrimaryUserPic($category->id) }}"
                                     alt="{{ $category->name }}"/>
                            </a>
                            <p class="categories-subcategories-margin">{{ str_limit($category->name, 30) }}</p>
                            <a href="/Items/{{$category->name}}/{{ $category->id }}" class="readmore categories-subcategories-margin">Read More...</a>
                        </li>
                        <script type="application/ld+json">
                        {
                          "@context": "http://schema.org/",
                          "@type": "Product",
                          "name": "{{ $category->name }}",
                          "image": "{{ \App\Category::GetPrimaryUserPic($category->id) == "not" ? asset('images/no.gif') : \App\Category::GetPrimaryUserPic($category->id) }}",
                          "description": "{{ $category->content }}"
                        }
                        </script>
                    @endforeach
                @endforeach
            </ul>
        </article>
        <article class="col-md-3 advertise">
            <h4>Advertise</h4>
        </article>
    </section>
@endsection
