@extends('app')
@section('content')
    <div class="col-md-9">
        @if(empty($subcategory))
            <h1>Sorry! No Result</h1>
        @else
            @foreach($subcategory as $subcat)
                <ul class="search">
                    <li>
                        <a class="clearfix" href="/jobs/{{ \App\Category::find($subcat->category_id)->name }}/{{ $subcat->name }}/{{ $subcat->category_id }}/{{ $subcat->id }}"><img
                                    class="myimage" data-subcategory="{{ $subcat->id }}" onerror="imgError(this)"
                                    src="http://admin.yourserviceconnection.com/upload/subcategories/images/thumbs/{{ $subcat->image }}"
                                    alt=""></a>
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