@extends('app')
@section('content')
    @if(Session::has('message'))
        <h3 class="alert-success">{{ Session::get('message') }}</h3>
    @endif
    <div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">My
                    Account</a></li>
            <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Service
                    Provider Profile</a></li>
            <li role="presentation"><a href="#premium" aria-controls="premium" role="tab" data-toggle="tab">Premium
                    Service</a></li>
            <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Messages</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="home">
                <div class="container">
                    @include('partial.profile.user_profile')
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="profile">
                <figure class="container">
                    @if(\App\User::find(Auth::id())->hasRole('Provider'))
                        @include('partial.profile.provider_profile')
                    @else
                        @include('partial.profile.user_profile')
                    @endif
                    @if(!\Auth::user()->hasRole('Provider'))
                        <button class="btn btn-red" onclick="window.location.href = '/profile/create'">Become A
                            Provider/Supplier
                        </button>
                    @else
                        <button class="btn btn-red"
                                onclick="window.location.href = '/profile/{{ Auth::user()->provider->id }}/edit'">Edit /
                            Add Services
                        </button>
                    @endif
                </figure>
            </div>
            <div role="tabpanel" class="tab-pane" id="premium">
                @if(!empty($plans))
                    @foreach($plans as $plan)
                        {{ $plan->plan_type }}<br>
                    @endforeach
                @endunless
                        <a href="/plan/show" class="btn btn-danger">Add More Plans</a>
            </div>
        </div>

    </div>

    {!! Html::script('lib/bootstrapWizard.js') !!}

@endsection