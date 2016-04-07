@extends('user')
@section('main-content')
    {!! Auth::check() ? "<div class='col-md-10 content-main'>" : "<div class='col-md-12 content-main'>"  !!}
        <h4 class="text-center no-job-heading">Sorry profile page you are looking not found.</h4>
        <p class="text-center no-job-para">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deleniti ducimus eius id, labore maiores
            maxime pariatur ullam? Animi deleniti eaque enim exercitationem explicabo labore molestias provident quo
            quos, voluptatem. Iure?
        </p>
    <p class="text-center red" style="background: #7f0508; color: white; padding: 10px 5px; ">Or Could be because category not found, please select other category for job <a style="color: inherit" href="/profile/{{ Auth::user()->provider->id }}/edit">Edit Profile</a></p>
    </div>
@endsection