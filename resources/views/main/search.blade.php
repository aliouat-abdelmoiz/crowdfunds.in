@extends('app')
@section('content')
    <div class="col-md-9">
        @if(empty($subcategory))
            <h1>Sorry! No Result</h1>
        @else
            @foreach($subcategory as $subcat)
                <ul class="search">
                    <li>
                        <a href="/jobs/{{ \App\Category::find($subcategory->category->id)->name }}/{{ $subcategory->name }}">
                            <div class="overlay-link">Send Job</div>
                            <img class="thumbimg" onerror="imgErrorSub(this)" src="{{ !isset($subcategory->image)||$subcategory->image == '' ? '/images/no-logo.png' : 'https://admin.yourserviceconnection.com/upload/subcategories/images/thumbs/' . $subcategory->image }} " alt=""/>
                        </a>
                        <a class="clearfix" href="/jobs/{{ \App\Category::find($subcat->category_id)->name }}/{{ $subcat->name }}/{{ $subcat->category_id }}/{{ $subcat->id }}">
                            <h5>{{ $subcat->name }}</h5></a>
                        <small>{{ \App\Category::find($subcat->category_id)->name }}</small>
                        <p>{{ str_limit(\App\Category::find($subcat->category_id)->content, 50) }}</p>
                    </li>
                </ul>
            @endforeach
        @endif
    </div>
    <article class="col-md-3 advertise">
        <h4>Advertise</h4>
    </article>
@endsection
