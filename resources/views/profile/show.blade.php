@extends('user')
@section('title')
    @if(Auth::check())
        @if(Auth::user()->hasRole('Provider'))
            {{ $profile[0]->name }} Profile - Your Service Connection
        @endif
    @endif
@endsection
@section('main-content')
    {!! Auth::guest() ? "
    <div class='col-md-12 profile profile-page'>" : "
        <div class='col-md-10 profile-page'>" !!}
    @foreach($profile as $p)
        @if($p->youtube != "" || !empty($p->youtube))
            <div class="row youtube">
                <iframe width="100%" height="200"
                        src="https://www.youtube.com/embed/{{ $p->youtube }}"
                        frameborder="0" allowfullscreen></iframe>
            </div>
        @endif
        <div class="col-md-1">
            <img src="{{ $p->logo == "" || null ? $p->user->avatar : "/uploads/users/logos/" . $p->logo }}"
                 onerror="imgError(this)"
                 class="img small-image" alt="">
        </div>
        <div class="col-md-11">
            <h3>
                {{ $p->name }}
                @if(Auth::check())
                    Profile {!! $p->user->id == Auth::user()->id ? "<a class='btn btn-danger'
                                                                               href='/profile/$p->id/edit'>Edit
                                Profile</a>"
                            : '' !!}
                @endif
            </h3>

            <p><a class="profile_link website" href="{{ $p->website }}">Visit Provider Website</a></p>
        </div>
        @if($p->testimonial != "" || !empty($p->testimonial))
            <div class="row clearfix">
                <div class="col-md-12">
                    <p class="text">{{ $p->testimonial }}</p>
                </div>
            </div>
        @endif
        <div class="col-md-12">
            <p><b>{{ $p->user->name }} Provide Service in range - {{ $p->range }} Miles</b></p>
        </div>
    @endforeach
    @if(empty($select_category_name) || empty($select_subcategory_name))
        <h4>You should select some categories or subcategories otherwise you will not recieve any job.</h4>
    @else
        <div class="row clearfix">
            <div class="col-md-12">
                <ul class="cat-subcat">
                    <li class="add-margin"><b>Categories</b></li>
                    @foreach($select_category_name as $cat)
                        <li>{{ $cat->name }}</li>
                    @endforeach
                </ul>
                <ul class="cat-subcat">
                    <li class="add-margin"><b>Subcategories</b></li>
                    @foreach($select_subcategory_name as $cat)
                        <li>{{ $cat->name }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    <hr>
    <div class="row">
        <div class="col-md-12">
            @foreach($profile[0]->projects as $project)
                @if(empty($project))
                    <h4>Currently No Project Done By</h4>
                @else
                    <ul class="cat-subcat">
                        <li>
                            <b>Project Title : </b>{{ $project->title }}
                            - {{ \Carbon\Carbon::parse($project->updated_at)->diffForHumans() }}
                            {!! $project->feedback == 'null' || empty($project->feedback) ? '' : "" !!}
                            <p>
                                <small>Job Status : {{ $project->ended == 1 ? "Ended" : "Running" }}</small>
                            </p>
                            <p>

                            <div class="endedRate clearall" id="{{$project->id}}"
                                 data-project="{{$project->id}}" data-score="{{ $project->feedback }}"></div>
                            </p>
                        </li>
                    </ul>
                @endif
            @endforeach
        </div>
    </div>
    {!! "
</div>
" !!}
@endsection
@section('script')
    <script src="/lib/remodal/dist/remodal.min.js"></script>
    <script src="/lib/jquery.raty.js"></script>
    <script>
        $('.endedRate').raty({
            readOnly: true,
            score: function () {
                return $(this).attr('data-score');
            }
        });
    </script>
@endsection