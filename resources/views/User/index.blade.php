@extends('user')
@section('title')
    @if(!Auth::user()->projects->isEmpty())
        {{ Auth::user()->projects->last()->title }} - Your Service Connections
    @else
        {{ Auth::user()->name }} - Your Service Connection
    @endif
@endsection
@section('main-content')
    <div class="col-md-10 content-main">
        @if(Auth::user()->projects->isEmpty())
            <h4 class="text-center no-job-heading">No job post do you to send new job ?</h4>
            <p class="text-center no-job-para">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deleniti ducimus eius id, labore maiores
                maxime pariatur ullam? Animi deleniti eaque enim exercitationem explicabo labore molestias provident quo
                quos, voluptatem. Iure?
            </p>
        @else
            <ul class="project-list">
                @foreach(Auth::user()->projects->reverse() as $project)
                    <li>
                        <h3 class="text-capitalize">Posted
                            By {{ $project->user_id == Auth::id() ? 'You' : \App\User::getUsername($project->user_id) }}
                            - {{ str_limit($project->title, 20) }}</h3>

                        <p>{{ str_limit($project->body, 200) }}</p>

                        <p>
                            <a href="/jobs/show/{{ $project->id }}">Job Detail</a>
                            <span class="pull-right">
                                {{ $project->created_at->diffForHumans() }} in
                                {{ \App\Category::find($project->categories_id)->name }}
                                @if($project->range == 0)
                                    Visible to {{ Auth::user()->userinfo->city }}
                                @elseif($project->range == 1)
                                    Visible to {{ Auth::user()->userinfo->state }}
                                @else
                                    Visible InRange
                                @endif
                            </span>
                        </p>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection