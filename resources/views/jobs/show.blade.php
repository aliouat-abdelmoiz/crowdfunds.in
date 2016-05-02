@extends('user')
@section('title')
    {{ $project->title }} - Your Service Connection
@endsection
<meta name="_token" content="{{ csrf_token() }}">
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
                <li>
                    <h3 class="text-capitalize">Posted
                        By {{ $project->user_id == Auth::id() ? 'You' : \App\User::getUsername($project->user_id) }}
                        - {{ str_limit($project->title, 20) }} and {{ count($providers) }} Applicant.</h3>

                    <p>{{ $project->body }}</p>

                    <p>
                        <a href="/user">Go Back</a>

                    <form action="/jobs/delete" method="POST">
                        {!! Form::token() !!}
                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                        <input type="submit" class="btn btn-danger" value="Delete Job {{ $project->title }}">
                    </form>
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
            </ul>
            <ul class="provider-list">
                @if($project->provider_id == "" || $project->provider_id == null)
                    @if(empty($providers))
                        <p>No Applicant For This Job</p>
                    @endif
                    @foreach($providers as $provider)
                        <li>
                            <img onerror="imgError(this)"
                                 src="{{ \App\Provider::find($provider->id)->user->avatar == "" ? \App\Provider::find($provider->id)->logo :  \App\Provider::find($provider->id)->user->avatar}}"
                                 class="small-image pull-left" alt="">

                            <p class="pull-right bold">{{ $provider->name }}</p>

                            <p class="pull-right low-opacity">Job
                                Applied {{ \Carbon\Carbon::parse($provider->cdate)->diffForHumans() }}</p>

                            <p class="pull-right">
                                <a href="/profile/{{ $provider->id }}">Provider Profile</a>
                                &nbsp;<span class="low-opacity">|</span>&nbsp;
                                <a href="/message">Messages</a>
                                &nbsp;<span class="low-opacity">|</span>&nbsp;
                                <a onclick="javascript:hire($(this).data('project-id'), $(this).data('provider-id'))"
                                   data-provider-id="{{ $provider->id }}" data-project-id="{{ $project->id }}"
                                   href="#">Hire {{ $provider->name }} For {{ $project->title }}</a>
                            </p>
                        </li>
                    @endforeach
                @endif
                @if($project->provider_id != null || !empty($project->provider_id))
                    <li>
                        <p></p>
                        <img src="{{ \App\Provider::find($project->provider->id)->user->avatar == "" ? "/uploads/users/logos/" . \App\Provider::find($project->provider->id)->logo :  \App\Provider::find($project->provider->id)->user->avatar}}"
                             class="small-image pull-left" onerror="imgError(this)" alt="">

                        <p class="pull-right bold">{{ \App\Provider::find($project->provider_id)->name }}</p>

                        <p class="pull-right low-opacity">{{ $project->ended == 0 ? "Job Started" : "Job Ended" }} {{ \Carbon\Carbon::parse($project->updated_at)->diffForHumans() }}</p>

                        <p class="pull-right">
                            <a href="/profile/{{ $project->provider_id }}">Provider Profile</a>
                            &nbsp;<span class="low-opacity">|</span>&nbsp;
                            <a href="/message">Messages</a>
                            &nbsp;<span class="low-opacity">|</span>&nbsp;
                            {!! $project->ended == 0 ? '<a data-project="' . $project->id . '" data-provider="' . $project->provider_id . '"
                            class="endContract" href="#">End Contract</a>' : '<p><div class="endedRate" id="' . $project->id . '" data-score="' . $project->feedback . '" data-project="' . $project->id . '" ></div></p>' !!}
                        </p>
                    </li>
                @endif
            </ul>
        @endif
    </div>

@section('script')

    <script src="/lib/remodal/dist/remodal.min.js"></script>
    <script src="/lib/jquery.raty.js"></script>
    <link rel="stylesheet" href="/lib/remodal/dist/remodal.css">
    <link rel="stylesheet" href="/lib/remodal/dist/remodal-default-theme.css">
    <link rel="stylesheet" href="/lib/jquery.raty.css">

    <div class="remodal" data-remodal-id="modal">
        <button data-remodal-action="close" class="remodal-close"></button>
        <h1>Rate It</h1>
        <p class="alert-danger rate_error">Can't Rate 0</p>
        <p>
            Send Feedback to your contractor profile, how you feel ?
        </p>
        <p>
        <div class="rateme"></div>
        </p><br>
        <button data-remodal-action="cancel" class="remodal-cancel">Cancel</button>
        <button data-remodal-action="confirm" class="remodal-confirm">OK</button>
    </div>

    <script>
        var inst = $('[data-remodal-id=modal]').remodal();
        $('.endedRate').raty({
            readOnly: true,
            score: function () {
                return $(this).attr('data-score');
            }
        });
    </script>

@endsection

@endsection